SELECT institucion, acto_id, carrera_id, programa_id, estudiante_id, indice, 1 AS solicitud, control
created, modified
FROM graduandos
ORDER BY id