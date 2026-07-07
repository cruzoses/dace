SELECT 1 AS carrera_id, programa_id, trayecto_id, asignatura_id, CAST(nota_minima AS UNSIGNED) AS nota_minima, created, modified
FROM asignatura_programas AS mallas
ORDER BY id