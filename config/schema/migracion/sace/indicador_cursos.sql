SELECT curso_id,
CASE nombre
	WHEN 'TRIMESTRE 1' THEN 1
  WHEN 'TRIMESTRE 2' THEN 2
  WHEN 'TRIMESTRE 3' THEN 3
  WHEN 'P.E.R' THEN 4
  WHEN 'C.I.U' THEN 5
  WHEN 'TRIMESTRE' THEN 7
  WHEN 'SEMESTRE 1T' THEN 8
  WHEN 'SEMESTRE 2T' THEN 9  
	ELSE 10
END AS indicador_id, desde, hasta, 
CAST(escala AS UNSIGNED) AS escala_nota,
CAST(ponderacion AS UNSIGNED) AS porcentaje, created, modified
FROM contenidos AS indicador_cursos
ORDER BY id