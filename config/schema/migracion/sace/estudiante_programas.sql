SELECT CAST(ST.id AS UNSIGNED) AS estudiante_id,
P.carrera_id, EP.programa_id, EP.sede_id, EP.fecha_egreso, EP.cohorte, EP.indice, EP.culminado, EP.activo, EP.created, EP.modified
FROM estudiante_programas EP
INNER JOIN students ST ON ST.control = EP.estudiante_id
INNER JOIN programas P ON P.id = EP.programa_id
WHERE EP.estudiante_id IN( SELECT control FROM students )
ORDER BY EP.id