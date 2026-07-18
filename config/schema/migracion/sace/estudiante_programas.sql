SELECT ep.estudiante_id, pr.carrera_id, ep.programa_id, ep.sede_id, IFNULL(CAST(ep.periodo_id AS UNSIGNED),151) AS periodo_id, ep.fecha_egreso, ep.cohorte, ep.indice,
  ep.culminado, ep.activo, ep.created, ep.modified
FROM estudiante_programas ep
LEFT JOIN programas pr ON pr.id = ep.programa_id
ORDER BY ep.id