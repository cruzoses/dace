SELECT codigo, nombre, horas_teoricas, horas_practicas, frecuencia, tipo_nota AS `calificacion`, creditos, costo,
requisitos, convalidacion, 1 AS grupo_asignatura_id, activa, created, modified, id AS `control`
FROM asignaturas
ORDER BY id