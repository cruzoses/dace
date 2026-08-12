#!/usr/bin/env python3
"""
Batch processor: populate tablanotas.programa_id from the malla of each
student's program(s) (estudiante_programas).

Rules per tablanotas row (only rows whose programa_id = 0 are touched):
  * 0 matches  -> programa_id stays 0, condicion set to 1
  * 1 match    -> programa_id = that program, condicion stays 0
  * 2+ matches -> programa_id = smallest matching program, condicion = 1
  * student with no programs in estudiante_programas -> treated as 0 matches
  * rows with programa_id != 0 -> never touched

Usage:
  python -u batch_programa_tablanotas.py
  python -u batch_programa_tablanotas.py --host localhost --user desoft --password secret --db gesaca
  python -u batch_programa_tablanotas.py --dry-run   # only report stats, no writes
"""

import argparse
import sys
import time

try:
    import pymysql
    import pymysql.cursors
except ImportError:
    print("ERROR: pymysql no instalado. Ejecuta: pip install pymysql")
    sys.exit(1)


def main():
    parser = argparse.ArgumentParser(description='Batch processor: populate tablanotas.programa_id from mallas.')
    parser.add_argument('--host', default='localhost')
    parser.add_argument('--port', default=3306, type=int)
    parser.add_argument('--user', default='desoft')
    parser.add_argument('--password', default='secret')
    parser.add_argument('--db', default='gesaca')
    parser.add_argument('--dry-run', action='store_true',
                        help='solo calcula estadisticas, no escribe en la base de datos')
    args = parser.parse_args()

    t0 = time.time()
    print("=" * 60, flush=True)
    print("  BATCH PROGRAMA TABLANOTAS - D.A.C.E.", flush=True)
    print("=" * 60, flush=True)
    print("  Host: %s:%d  User: %s  DB: %s" % (args.host, args.port, args.user, args.db), flush=True)
    if args.dry_run:
        print("  Modo: DRY-RUN (no se escribira nada)", flush=True)
    print("=" * 60, flush=True)

    try:
        conn = pymysql.connect(host=args.host, port=args.port, user=args.user,
                               password=args.password, database=args.db, charset='utf8mb4')
    except pymysql.Error as e:
        print("\nERROR de conexion: %s" % e, flush=True)
        sys.exit(1)

    cur = conn.cursor(pymysql.cursors.DictCursor)

    print("\n[1/4] Cargando mallas...", flush=True)
    cur.execute("SELECT programa_id, asignatura_id FROM mallas")
    mallas_data = cur.fetchall()
    mallas_por_programa = {}
    for m in mallas_data:
        mallas_por_programa.setdefault(m['programa_id'], set()).add(m['asignatura_id'])
    print("  %d registros, %d programas con malla" % (
        len(mallas_data), len(mallas_por_programa)), flush=True)

    print("[2/4] Cargando estudiante_programas...", flush=True)
    cur.execute("SELECT estudiante_id, programa_id FROM estudiante_programas")
    ep_data = cur.fetchall()
    ep_por_estudiante = {}
    for ep in ep_data:
        ep_por_estudiante.setdefault(ep['estudiante_id'], []).append(ep['programa_id'])
    print("  %d registros, %d estudiantes con programas" % (
        len(ep_data), len(ep_por_estudiante)), flush=True)

    print("[3/4] Cargando tablanotas...", flush=True)
    cur.execute("SELECT id, estudiante_id, programa_id, asignatura_id FROM tablanotas")
    notas_data = cur.fetchall()
    print("  %d registros" % len(notas_data), flush=True)

    t_load = time.time() - t0

    print("[4/4] Procesando %d registros... (carga: %.1fs)" % (len(notas_data), t_load), flush=True)

    update_list = []
    total_unicos = 0
    total_ambiguos = 0
    total_sin_match = 0
    total_omitidos = 0
    total_sospechosos = 0

    t_proc = time.time()
    n_count = 0
    for nota in notas_data:
        n_count += 1
        if nota['programa_id'] != 0:
            total_omitidos += 1
            pid = nota['programa_id']
            if pid not in mallas_por_programa or nota['asignatura_id'] not in mallas_por_programa[pid]:
                total_sospechosos += 1
            continue

        progs = ep_por_estudiante.get(nota['estudiante_id'], [])
        matches = [p for p in progs if nota['asignatura_id'] in mallas_por_programa.get(p, ())]

        if not matches:
            update_list.append((0, 1, nota['id']))
            total_sin_match += 1
        elif len(matches) == 1:
            update_list.append((matches[0], 0, nota['id']))
            total_unicos += 1
        else:
            update_list.append((min(matches), 1, nota['id']))
            total_ambiguos += 1

        if n_count % 100000 == 0:
            print("  %d/%d procesados..." % (n_count, len(notas_data)), flush=True)

    t_calc = time.time() - t_proc

    print("", flush=True)
    print("  Resultado del procesamiento:", flush=True)
    print("  Llenados unicos (condicion 0):   %d" % total_unicos, flush=True)
    print("  Ambiguos -> menor programa (c1): %d" % total_ambiguos, flush=True)
    print("  Sin coincidencia (condicion 1):  %d" % total_sin_match, flush=True)
    print("  Omitidos (programa_id != 0):     %d  [sospechosos: %d]" % (
        total_omitidos, total_sospechosos), flush=True)
    print("  Total a actualizar:              %d" % len(update_list), flush=True)

    if not update_list:
        print("\n  No hay nada que actualizar.", flush=True)
        cur.close()
        conn.close()
        return

    if args.dry_run:
        print("\n  DRY-RUN: no se escribio nada.", flush=True)
        cur.close()
        conn.close()
        return

    print("\nEscribiendo en base de datos...", flush=True)
    t_write = time.time()

    cur.executemany(
        "UPDATE tablanotas SET programa_id=%s, condicion=%s, modified=NOW() WHERE id=%s",
        update_list
    )
    conn.commit()

    t_write = time.time() - t_write
    t_total = time.time() - t0

    print("  %d filas actualizadas" % cur.rowcount, flush=True)

    print("\n" + "=" * 60, flush=True)
    print("  RESUMEN FINAL", flush=True)
    print("=" * 60, flush=True)
    print("  Registros procesados:     %d" % len(notas_data), flush=True)
    print("  Llenados unicos:          %d" % total_unicos, flush=True)
    print("  Ambiguos (condicion 1):   %d" % total_ambiguos, flush=True)
    print("  Sin coincidencia (c1):    %d" % total_sin_match, flush=True)
    print("  Omitidos ya asignados:    %d  [sospechosos: %d]" % (
        total_omitidos, total_sospechosos), flush=True)
    print("  Tiempo carga datos:       %.1f s" % t_load, flush=True)
    print("  Tiempo calculo:           %.1f s" % t_calc, flush=True)
    print("  Tiempo escritura:         %.1f s" % t_write, flush=True)
    print("  Tiempo total:             %.1f s" % t_total, flush=True)
    print("=" * 60, flush=True)

    cur.close()
    conn.close()


if __name__ == '__main__':
    main()