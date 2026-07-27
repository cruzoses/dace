SELECT uc.codigo, uc.nombre, uc.creditos, tn.calificacion,
CASE 
  WHEN tn.calificacion = 'A' THEN uc.creditos * 20
  WHEN tn.calificacion = 'R' THEN uc.creditos * 1
  ELSE tn.calificacion * uc.creditos
END AS acumulado
FROM tablanotas tn
INNER JOIN asignaturas uc ON uc.id = tn.asignatura_id
WHERE tn.estudiante_id = 17913 