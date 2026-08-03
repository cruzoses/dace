SELECT fecha, CAST(detalle AS VARCHAR(50) ) AS descripcion, CAST(descripcion AS VARCHAR(255)) AS detalle, ponderacion,
CAST(contenido_id AS UNSIGNED) AS indicador_curso_id, 1 AS activo, created, modified
FROM contenido_cursos
ORDER BY id