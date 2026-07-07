SELECT sede_id, 
CASE periodo_id 
  WHEN 155 THEN 154 
  WHEN 156 THEN 155
  WHEN 157 THEN 156
  WHEN 158 THEN 157
  WHEN 159 THEN 158
  WHEN 160 THEN 159
  WHEN 161 THEN 160
  WHEN 162 THEN 161
  WHEN 163 THEN 162
  WHEN 164 THEN 163
  WHEN 165 THEN 164
ELSE periodo_id
END AS periodo_id,
carrera_id, trayecto_id, CAST(programa_id AS UNSIGNED) AS programas, asignatura_id, CAST(docentes AS UNSIGNED ) AS profesores, 
CASE docente_id WHEN 0 THEN 221 ELSE docente_id END AS docente_id,
seccion, cupos, horario, aula_id, cerrado, activo, created, modified, CAST(id AS UNSIGNED) AS curso_id
FROM cursos
ORDER BY id