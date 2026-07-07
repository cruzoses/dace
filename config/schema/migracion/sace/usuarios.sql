SELECT cedula,nombres,apellidos,fecha_nacimiento,genero AS sexo,email, telefonos, username, password, activo, created, modified, rol_id AS rol
FROM usuarios
WHERE nombres <> 'NOMBRES' AND apellidos <> 'APELLIDOS'
ORDER BY id