SELECT a.cedula, a.nombres, a.apellidos, a.fecha_nacimiento, a.sexo, a.expediente
FROM estudiante_cursos b 
INNER JOIN estudiantes a ON a.id = b.estudiante_id
WHERE b.curso_id = 4671
ORDER BY a.apellidos, a.nombres