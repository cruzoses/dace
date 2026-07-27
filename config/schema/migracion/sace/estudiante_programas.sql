SELECT ep.estudiante_id, pr.carrera_id, ep.programa_id, ep.sede_id, IFNULL(CAST(ep.periodo_id AS UNSIGNED),151) AS periodo_id, ep.fecha_egreso, ep.cohorte, 
  CAST(ep.indice AS UNSIGNED) AS isa, 0 AS ira, ep.culminado, 
  CASE WHEN periodo_id IS NULL THEN 1 ELSE 0 END AS congelado,
  ep.activo, ep.created, ep.modified
FROM estudiante_programas ep
LEFT JOIN programas pr ON pr.id = ep.programa_id
WHERE ep.programa_id IS NOT NULL
ORDER BY ep.id