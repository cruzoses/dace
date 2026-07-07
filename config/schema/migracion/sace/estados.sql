SELECT pais_id,nombre,
CASE WHEN ISNULL(created) THEN now() ELSE created END AS created,
CASE WHEN ISNULL(modified) THEN now() ELSE created END AS modified
FROM estados
ORDER BY id