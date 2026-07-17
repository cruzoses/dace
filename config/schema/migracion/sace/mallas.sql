SELECT CAST(p.carrera_id AS UNSIGNED) AS carrera_id, mallas.programa_id, CAST(mallas.trayecto_id AS UNSIGNED) AS trayecto_id, mallas.asignatura_id, 
mallas.nota_minima, mallas.created, mallas.modified
FROM asignatura_programas AS mallas
INNER JOIN programas p ON p.id = mallas.programa_id
ORDER BY mallas.id 