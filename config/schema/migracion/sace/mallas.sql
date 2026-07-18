UPDATE asignatura_programas SET nota_minima = NULL WHERE nota_minima <> '16';
SELECT CAST(p.carrera_id AS UNSIGNED) AS carrera_id, mallas.programa_id, CAST(mallas.trayecto_id AS UNSIGNED) AS trayecto_id, mallas.asignatura_id, 
CAST(mallas.nota_minima AS UNSIGNED) AS nota_minima, mallas.created, mallas.modified
FROM asignatura_programas AS mallas
INNER JOIN programas p ON p.id = mallas.programa_id
ORDER BY mallas.id 