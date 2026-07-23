#!/usr/bin/env python3
"""
Batch processor: sync situacion_estudiantes from tablanotas for ALL students.
Bulk-optimized: pre-loads all reference data, processes in memory, batch writes.

Usage:
  python -u batch_situacion.py
  python -u batch_situacion.py --host localhost --user desoft --password secret --db gesaca
"""

import argparse
import re
import sys
import time

try:
    import pymysql
    import pymysql.cursors
except ImportError:
    print("ERROR: pymysql no instalado. Ejecuta: pip install pymysql")
    sys.exit(1)


def parse_convalidacion(texto):
    texto = texto.strip()
    if not texto:
        return []
    partes = re.split(r'\s*[;,]\s*|\s+o\s+', texto, flags=re.IGNORECASE)
    return [p.strip().upper() for p in partes if p.strip()]


def es_aprobada(calificacion, nota_minima):
    val = calificacion.strip().upper()
    if val == 'A':
        return True
    if val in ('R', 'IN', ''):
        return False
    try:
        return int(val) >= int(nota_minima)
    except (ValueError, TypeError):
        return False


def calificacion_a_numero(calificacion):
    val = str(calificacion).strip().upper()
    if val == 'A':
        return 20
    if val in ('R', 'IN', ''):
        return 0
    try:
        return int(val)
    except (ValueError, TypeError):
        return 0


def buscar_convalidacion(estudiante_id, codigos_alternativos, nota_minima_prog,
                         asignaturas_by_codigo, notas_por_estudiante, mallas_nota_minima):
    if not codigos_alternativos:
        return None
    for cod in codigos_alternativos:
        alt = asignaturas_by_codigo.get(cod)
        if not alt:
            continue
        alt_id = alt['id']
        malla_mm = mallas_nota_minima.get(alt_id)
        if malla_mm is not None:
            nota_minima_alt = int(malla_mm)
        elif alt.get('nota_minima') is not None:
            nota_minima_alt = int(alt['nota_minima'])
        else:
            nota_minima_alt = int(nota_minima_prog)
        notas_alt = notas_por_estudiante.get(estudiante_id, {}).get(alt_id, [])
        if not notas_alt:
            continue
        ultima_alt = max(notas_alt, key=lambda x: calificacion_a_numero(x['calificacion']))
        if es_aprobada(str(ultima_alt['calificacion']), nota_minima_alt):
            return {
                'calificacion': str(ultima_alt['calificacion']),
                'seccion': ultima_alt['seccion'],
                'responsable': ultima_alt['responsable'],
                'periodo_id': ultima_alt['periodo_id'],
            }
    return None


def main():
    parser = argparse.ArgumentParser(description='Batch processor: sync situacion_estudiantes.')
    parser.add_argument('--host', default='localhost')
    parser.add_argument('--port', default=3306, type=int)
    parser.add_argument('--user', default='desoft')
    parser.add_argument('--password', default='secret')
    parser.add_argument('--db', default='gesaca')
    args = parser.parse_args()

    t0 = time.time()
    print("=" * 60, flush=True)
    print("  BATCH SITUACION ESTUDIANTES - D.A.C.E.", flush=True)
    print("=" * 60, flush=True)
    print("  Host: %s:%d  User: %s  DB: %s" % (args.host, args.port, args.user, args.db), flush=True)
    print("=" * 60, flush=True)

    try:
        conn = pymysql.connect(host=args.host, port=args.port, user=args.user,
                               password=args.password, database=args.db, charset='utf8mb4')
    except pymysql.Error as e:
        print("\nERROR de conexion: %s" % e, flush=True)
        sys.exit(1)

    cur = conn.cursor(pymysql.cursors.DictCursor)

    print("\n[1/7] Cargando programas...", flush=True)
    cur.execute("SELECT id, nota_minima FROM programas")
    programas_map = {}
    for r in cur.fetchall():
        programas_map[r['id']] = int(r['nota_minima']) if r['nota_minima'] is not None else 10

    print("[2/7] Cargando mallas...", flush=True)
    cur.execute("SELECT programa_id, carrera_id, asignatura_id, trayecto_id, nota_minima FROM mallas")
    mallas_data = cur.fetchall()
    mallas_por_programa = {}
    mallas_nota_minima = {}
    for m in mallas_data:
        key = (m['programa_id'], m['carrera_id'])
        if key not in mallas_por_programa:
            mallas_por_programa[key] = []
        mallas_por_programa[key].append({'asignatura_id': m['asignatura_id'], 'trayecto_id': m['trayecto_id']})
        if m['asignatura_id'] not in mallas_nota_minima and m['nota_minima'] is not None:
            mallas_nota_minima[m['asignatura_id']] = int(m['nota_minima'])
    print("  %d registros" % len(mallas_data), flush=True)

    print("[3/7] Cargando asignaturas...", flush=True)
    cur.execute("SELECT id, codigo, nota_minima, convalidacion, creditos FROM asignaturas")
    asignaturas_list = cur.fetchall()
    asignaturas_by_id = {}
    asignaturas_by_codigo = {}
    for a in asignaturas_list:
        asignaturas_by_id[a['id']] = a
        if a['codigo']:
            asignaturas_by_codigo[a['codigo'].strip().upper()] = a
    print("  %d asignaturas" % len(asignaturas_list), flush=True)

    print("[4/7] Cargando estudiante_programas...", flush=True)
    cur.execute("SELECT estudiante_id, programa_id, carrera_id, periodo_id FROM estudiante_programas")
    ep_data = cur.fetchall()
    estudiante_programas = {}
    for ep in ep_data:
        sid = ep['estudiante_id']
        if sid not in estudiante_programas:
            estudiante_programas[sid] = []
        estudiante_programas[sid].append(ep)
    estudiantes_ids = sorted(estudiante_programas.keys())
    total = len(estudiantes_ids)
    print("  %d estudiantes con programas" % total, flush=True)

    print("[5/7] Cargando situacion_estudiantes...", flush=True)
    cur.execute("SELECT id, estudiante_id, programa_id, asignatura_id, calificacion FROM situacion_estudiantes")
    se_data = cur.fetchall()
    situacion_map = {}
    for s in se_data:
        key = (s['estudiante_id'], s['programa_id'], s['asignatura_id'])
        situacion_map[key] = s
    print("  %d registros" % len(se_data), flush=True)

    print("[6/7] Cargando tablanotas...", flush=True)
    cur.execute("SELECT estudiante_id, asignatura_id, periodo_id, calificacion, seccion, responsable FROM tablanotas")
    notas_data = cur.fetchall()
    notas_por_estudiante = {}
    for n in notas_data:
        sid = n['estudiante_id']
        aid = n['asignatura_id']
        if sid not in notas_por_estudiante:
            notas_por_estudiante[sid] = {}
        if aid not in notas_por_estudiante[sid]:
            notas_por_estudiante[sid][aid] = []
        notas_por_estudiante[sid][aid].append(n)
    print("  %d calificaciones" % len(notas_data), flush=True)

    t_load = time.time() - t0
    print("\n[7/7] Procesando %d estudiantes... (carga: %.1fs)\n" % (total, t_load), flush=True)

    grand_total_programas = 0
    grand_total_actualizados = 0
    grand_total_convalidaciones = 0
    update_list = []
    insert_list = []
    malla_insert_list = []

    t_proc = time.time()
    for i, sid in enumerate(estudiantes_ids, 1):
        progs = estudiante_programas[sid]
        est_actualizados = 0

        for prog in progs:
            pid = prog['programa_id']
            cid = prog['carrera_id']
            periodo_id = prog['periodo_id']
            mm_key = (pid, cid)
            mallas_prog = mallas_por_programa.get(mm_key, [])
            prog_nota_minima = programas_map.get(pid, 10)

            for m in mallas_prog:
                se_key = (sid, pid, m['asignatura_id'])
                if se_key not in situacion_map:
                    malla_insert_list.append((sid, pid, m['asignatura_id'], m['trayecto_id'], periodo_id))

            asignaturas_en_malla = {m['asignatura_id']: m for m in mallas_prog}
            situacion_prog = [s for s in se_data if s['estudiante_id'] == sid and s['programa_id'] == pid]
            ids_en_situacion = {s['asignatura_id'] for s in situacion_prog}
            todos_ids = ids_en_situacion | set(asignaturas_en_malla.keys())

            notas_est = notas_por_estudiante.get(sid, {})
            por_asignatura = {aid: notas_est[aid] for aid in todos_ids if aid in notas_est}

            for asignatura_id, lista_notas in por_asignatura.items():
                ultima = max(lista_notas, key=lambda x: calificacion_a_numero(x['calificacion']))
                cursada = len(lista_notas)
                asig = asignaturas_by_id.get(asignatura_id)
                creditos_asig = int(asig['creditos']) if asig and asig.get('creditos') else 1
                acumulado = 0
                for n in lista_notas:
                    val = str(n['calificacion']).strip().upper()
                    if val == 'A':
                        acumulado += 20 * creditos_asig
                    elif val == 'R':
                        acumulado += 0
                    else:
                        try:
                            acumulado += int(n['calificacion']) * creditos_asig
                        except (ValueError, TypeError):
                            acumulado += 0

                malla_mm = mallas_nota_minima.get(asignatura_id)
                if malla_mm is not None:
                    nota_minima = malla_mm
                elif asig and asig.get('nota_minima') is not None:
                    nota_minima = int(asig['nota_minima'])
                else:
                    nota_minima = prog_nota_minima

                cal_str = str(ultima['calificacion']).strip().upper()
                try:
                    aprobada = (cal_str == 'A') or (cal_str not in ('R', 'IN', '') and int(cal_str) >= nota_minima)
                except (ValueError, TypeError):
                    aprobada = False

                if not aprobada and asig and asig.get('convalidacion'):
                    codigos = parse_convalidacion(asig['convalidacion'])
                    nota_cv = buscar_convalidacion(
                        sid, codigos, prog_nota_minima,
                        asignaturas_by_codigo, notas_por_estudiante, mallas_nota_minima
                    )
                    if nota_cv:
                        ultima = nota_cv
                        grand_total_convalidaciones += 1

                se_key = (sid, pid, asignatura_id)
                existing = situacion_map.get(se_key)
                if existing:
                    update_list.append((ultima['calificacion'], ultima['seccion'], ultima['responsable'],
                                        ultima['periodo_id'], cursada, acumulado, existing['id']))
                    est_actualizados += 1
                elif asignatura_id in asignaturas_en_malla:
                    m = asignaturas_en_malla[asignatura_id]
                    insert_list.append((sid, pid, asignatura_id, m['trayecto_id'],
                                        ultima['periodo_id'], ultima['seccion'], ultima['calificacion'],
                                        ultima['responsable'], cursada, acumulado))
                    est_actualizados += 1

            for s in situacion_prog:
                aid = s['asignatura_id']
                if s.get('calificacion') is not None:
                    continue
                if aid in por_asignatura:
                    continue
                asig = asignaturas_by_id.get(aid)
                if not asig or not asig.get('convalidacion'):
                    continue
                codigos = parse_convalidacion(asig['convalidacion'])
                nota_cv = buscar_convalidacion(
                    sid, codigos, prog_nota_minima,
                    asignaturas_by_codigo, notas_por_estudiante, mallas_nota_minima
                )
                if nota_cv:
                    update_list.append((nota_cv['calificacion'], nota_cv['seccion'],
                                        nota_cv['responsable'], nota_cv['periodo_id'],
                                        0, 0, s['id']))
                    est_actualizados += 1
                    grand_total_convalidaciones += 1

            for aid, m in asignaturas_en_malla.items():
                if aid in por_asignatura:
                    continue
                se_key = (sid, pid, aid)
                if se_key in situacion_map:
                    continue
                asig = asignaturas_by_id.get(aid)
                if not asig or not asig.get('convalidacion'):
                    continue
                codigos = parse_convalidacion(asig['convalidacion'])
                nota_cv = buscar_convalidacion(
                    sid, codigos, prog_nota_minima,
                    asignaturas_by_codigo, notas_por_estudiante, mallas_nota_minima
                )
                if nota_cv:
                    insert_list.append((sid, pid, aid, m['trayecto_id'],
                                        nota_cv['periodo_id'], nota_cv['seccion'],
                                        nota_cv['calificacion'], nota_cv['responsable'],
                                        1, 0))
                    est_actualizados += 1
                    grand_total_convalidaciones += 1

            for aid in por_asignatura:
                se_key = (sid, pid, aid)
                if se_key not in situacion_map:
                    situacion_map[se_key] = {'id': None, 'estudiante_id': sid,
                                              'programa_id': pid, 'asignatura_id': aid}

        grand_total_programas += len(progs)
        grand_total_actualizados += est_actualizados

        if i % 100 == 0 or i == total:
            pct = (i / total) * 100
            elapsed = time.time() - t_proc
            speed = i / elapsed if elapsed > 0 else 0
            remaining = (total - i) / speed if speed > 0 else 0
            print("  [%5.1f%%] Procesando estudiante %d de %d | %d actualizados | %.0f seg restantes" % (
                pct, i, total, grand_total_actualizados, remaining
            ), flush=True)

    t_calc = time.time() - t_proc

    insert_keys = {(s[0], s[1], s[2]) for s in insert_list}
    malla_orig = len(malla_insert_list)
    malla_insert_list = [m for m in malla_insert_list if (m[0], m[1], m[2]) not in insert_keys]
    malla_duplicados_cruz = malla_orig - len(malla_insert_list)

    seen_malla = set()
    malla_dedup = []
    for m in malla_insert_list:
        key = (m[0], m[1], m[2])
        if key not in seen_malla:
            seen_malla.add(key)
            malla_dedup.append(m)
    malla_duplicados_int = len(malla_insert_list) - len(malla_dedup)
    malla_insert_list = malla_dedup

    seen_insert = set()
    insert_dedup = []
    for s in insert_list:
        key = (s[0], s[1], s[2])
        if key not in seen_insert:
            seen_insert.add(key)
            insert_dedup.append(s)
    insert_duplicados = len(insert_list) - len(insert_dedup)
    insert_list = insert_dedup

    print("\n  Calculo completado en %.1f segundos" % t_calc, flush=True)
    print("  Malla inserts: %d (%d vs insert_list, %d internos)" % (
        len(malla_insert_list), malla_duplicados_cruz, malla_duplicados_int), flush=True)
    print("  Sync inserts:  %d (%d duplicados internos)" % (len(insert_list), insert_duplicados), flush=True)
    print("  Updates:       %d" % len(update_list), flush=True)
    print("  Convalidaciones: %d" % grand_total_convalidaciones, flush=True)

    print("\nEscribiendo en base de datos...", flush=True)
    t_write = time.time()

    if malla_insert_list:
        cur.executemany(
            "INSERT INTO situacion_estudiantes "
            "(estudiante_id, programa_id, asignatura_id, trayecto_id, periodo_id, "
            "cursada, acumulado, created, modified) "
            "VALUES (%s, %s, %s, %s, %s, 1, 1, NOW(), NOW())",
            malla_insert_list
        )
        print("  %d malla inserts" % len(malla_insert_list), flush=True)

    if insert_list:
        cur.executemany(
            "INSERT INTO situacion_estudiantes "
            "(estudiante_id, programa_id, asignatura_id, trayecto_id, periodo_id, "
            "seccion, calificacion, responsable, cursada, acumulado, created, modified) "
            "VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW())",
            insert_list
        )
        print("  %d sync inserts" % len(insert_list), flush=True)

    if update_list:
        cur.executemany(
            "UPDATE situacion_estudiantes "
            "SET calificacion=%s, seccion=%s, responsable=%s, periodo_id=%s, "
            "cursada=%s, acumulado=%s, modified=NOW() WHERE id=%s",
            update_list
        )
        print("  %d updates" % len(update_list), flush=True)

    conn.commit()
    t_write = time.time() - t_write
    t_total = time.time() - t0

    print("\n" + "=" * 60, flush=True)
    print("  RESUMEN FINAL", flush=True)
    print("=" * 60, flush=True)
    print("  Estudiantes procesados:    %d" % total, flush=True)
    print("  Programas procesados:      %d" % grand_total_programas, flush=True)
    print("  Asignaturas actualizadas:  %d" % grand_total_actualizados, flush=True)
    print("  Convalidaciones aplicadas: %d" % grand_total_convalidaciones, flush=True)
    print("  Tiempo carga datos:        %.1f s" % t_load, flush=True)
    print("  Tiempo calculo:            %.1f s" % t_calc, flush=True)
    print("  Tiempo escritura:          %.1f s" % t_write, flush=True)
    print("  Tiempo total:              %.1f s" % t_total, flush=True)
    print("=" * 60, flush=True)

    cur.close()
    conn.close()


if __name__ == '__main__':
    main()
