SELECT cedula, nombres, apellidos, fecha_nacimiento, sexo, email, telefonos, 1 AS departamento_id,
COALESCE(token, 'A1B2C3D4') AS token, usuario_id, activo, created, modified
FROM docentes