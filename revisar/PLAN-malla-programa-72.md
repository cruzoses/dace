# Plan de corrección — Malla programa 72 (T.S.U. Producción Agroalimentaria)

Fecha: 2026-08-11
BD: `gesaca` (BD de la aplicación CakePHP en `d:\Apache24\htdocs\dace`)

## Diagnóstico

### Caso reportado
- Estudiante **23170** (LUIS MANUEL MELENDEZ CASTRO), programa **72**, refleja **60 de 63 créditos aprobados** aunque tiene todas las asignaturas aprobadas.
- Estudiante **29059** (JUANA MARGARITA NARANJO), programa **72**: 44 notas aprobadas en `tablanotas`, pero la situación refleja solo **60 de 63**.

### Causa raíz
La **malla del programa 72 está mal estructurada**: la asignatura **1295 (PROTECCION INTEGRAL, 3 créditos)** está registrada **dos veces** (trayecto 1 y trayecto 2) en `mallas`. Además, la malla **no incluye** asignaturas que los estudiantes realmente cursaron y aprobaron:

- **1316** ELECTIVA I - ENTOMOLOGIA (1 cr)
- **1320** ELECTIVA II - EDAFOLOGIA (1 cr)
- **1323** FORMACION SOCIO POLITICA II (1 cr)

El total "63" proviene de `programas.creditos = 63` (coherente con la malla que duplica 1295: 42 filas = 63 créditos). Los créditos aprobados se calculan desde `situacion_estudiantes` sobre asignaturas **distintas** (41 filas = 60 créditos), por lo que la duplicación infla el total pero no los aprobados → **60/63**.

### Código que produce el cálculo
`src\Controller\DatosController.php` → `situacion()`:
- `totalCreditosPrograma = (int)$programa->programa->creditos` (línea 282) → 63.
- `totalCreditosAprobados += (int)$asig->asignatura->creditos` por fila aprobada de `situacion_estudiantes` (línea 308) → 60.
- La situación se regenera con `registrarDesdeMalla()` + `sincronizarDesdeHistorico()` (`actualizarsituacion`, línea 349).

### Números en la BD (gesaca)

| Fuente | Filas | Créditos | Detalle |
|---|---|---|---|
| `mallas` programa 72 (antes) | 42 | 63 | 1295 duplicada (tray 1 y 2) |
| `programas.creditos` (72) | - | 63 | total mostrado |
| `situacion_estudiantes` 23170 | 41 | 60 | todas aprobadas |
| `situacion_estudiantes` 29059 | 41 | 60 | todas aprobadas |
| `tablanotas` 23170 | 42 | - | incluye 1323, sin 1316/1320 |
| `tablanotas` 29059 | 44 | - | incluye 1316, 1320 y 1323 |

## Corrección aplicada (Opción A)

En `gesaca`:

1. `UPDATE mallas SET asignatura_id = 1323 WHERE programa_id = 72 AND trayecto_id = 2 AND asignatura_id = 1295;`
   - Reemplaza la 1295 duplicada del trayecto 2 por FORMACION SOCIO POLITICA II.
2. `INSERT INTO mallas (carrera_id, programa_id, trayecto_id, asignatura_id) VALUES (78, 72, 2, 1316), (78, 72, 3, 1320);`
   - Agrega las electivas I y II.
3. Reconstrucción de `situacion_estudiantes` para los 1230 estudiantes del programa 72 replicando la lógica de `sincronizarDesdeHistorico` (última/máxima nota, `cursada` = nº de notas, `acumulado` = Σ nota×créditos con A=20, R=0).

Resultado esperado de la malla (44 asignaturas / 63 créditos):
- Trayecto 1: 843, 844, 845, 847, 848, 1163, 1295
- Trayecto 2: 1290..1302 + 1316, 1324..1327, 1323
- Trayecto 3: 1303..1314 + 1320
- Trayecto 4: 1315, 1317, 1318, 1319, 1321, 1322

## Verificación (resultados reales, 2026-08-11)

Tras regenerar `situacion_estudiantes` (script `revisar/regenerar_situacion_72.php`,
que usa la lógica de la app: `registrarDesdeMalla` + `sincronizarDesdeHistorico`
con notas de `gesaca.tablanotas`):

- Malla programa 72: **44 filas / 44 asignaturas distintas / 63 créditos** (sin duplicados).
- `programas.creditos` (72) = 63 → total mostrado correcto.
- `situacion_estudiantes` programa 72: **54.120 filas** (1230 estudiantes × 44 asignaturas).
- Estudiante **29059**: **63/63** créditos aprobados (tiene notas de las 44 asignaturas). ✅
- Estudiante **23170**: **61/63** — solo le faltan las electivas **1316** (ELECTIVA I) y
  **1320** (ELECTIVA II) que **no tiene en `tablanotas`**. Refleja fielmente su historial;
  no es un error de la malla.

Nota: la app calcula el total desde `programas.creditos` (63) y los aprobados desde
`situacion_estudiantes`; la corrección de la malla elimina la inflación de 3 créditos
que causaba el reporte original de "60/63 con todo aprobado".

## Seguimiento

- Revisar los **7 programas** con asignaturas duplicadas en `gesaca.mallas`: **14, 15, 20, 27, 33, 72, 90** (mismo patrón de malla mal estructurada).
- Si el estudiante 23170 (u otros) efectivamente aprobaron las electivas 1316/1320, registrar esas notas en `tablanotas` y re-ejecutar "actualizar situación" (o el script `revisar/regenerar_situacion_72.php`).
- El script de regeneración se mantiene en `revisar/regenerar_situacion_72.php` por si hay que re-ejecutarlo.
