SELECT a.id + 1 AS id_faltante
FROM estudiantes a
LEFT JOIN estudiantes b ON b.id = a.id + 1
WHERE b.id IS NULL AND a.id < (SELECT MAX(id) FROM estudiantes)
ORDER BY a.id;