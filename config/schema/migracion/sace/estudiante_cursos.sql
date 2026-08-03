SELECT curso_id, estudiante_id, calificacion, recuperacion, definitiva, CAST(responsable AS VARCHAR(50)) AS analista,
activo, created, modified
FROM estudiante_cursos
ORDER BY curso_id,estudiante_id