SELECT codigo, nombre, horas_teoricas, horas_practicas, frecuencia, CAST(tipo_nota AS UNSIGNED) AS calificacion, creditos, costo,
requisitos, convalidacion, 1 AS grupo_asignatura_id, activa, created, modified
FROM asignaturas
ORDER BY id