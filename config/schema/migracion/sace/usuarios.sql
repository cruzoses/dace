SELECT cedula, nombres, apellidos, fecha_nacimiento, CAST(genero AS VARCHAR(1)) AS sexo, email, telefonos,
  username, password, twitter, instagram, facebook,
  activo, created, modified
FROM usuarios
ORDER BY id