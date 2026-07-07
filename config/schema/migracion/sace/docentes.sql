SELECT cedula, TRIM(nombres) AS nombres, TRIM(apellidos) AS apellidos, fecha_nacimiento, sexo, LOWER(email) AS email, telefonos, 1 AS departamento_id, 
CASE WHEN token IS NULL OR token = '' THEN '------' ELSE token END AS token, NULL AS usuario_id,activo, created, modified
FROM docentes
ORDER BY id