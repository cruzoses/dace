UPDATE mallas SET
carrera_id = (SELECT carrera_id FROM programas WHERE programas.id = mallas.programa_id)
