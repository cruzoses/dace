SELECT a.id + 1 AS id_faltante
FROM periodos a
LEFT JOIN periodos b ON b.id = a.id + 1
WHERE b.id IS NULL AND a.id < (SELECT MAX(id) FROM periodos)
ORDER BY a.id;