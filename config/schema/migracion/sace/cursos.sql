SELECT sede_id, periodo_id, carrera_id, trayecto_id, CAST(programa_id AS VARCHAR(40)) AS programas, asignatura_id,
CAST(docentes AS VARCHAR(40)) AS profesores,
CASE WHEN docente_id = 0 THEN 221 ELSE docente_id END AS docente_id, seccion, cupos, aula_id, horario,
cerrado, activo, created, modified
FROM cursos
ORDER BY ID