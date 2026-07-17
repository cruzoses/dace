SELECT CAST('V' AS CHAR(1) ) AS origen, e.cedula, e.nombres, e.apellidos, e.fecha_nacimiento, e.sexo, e.estado_civil, e.discapacitado, e.etnia, e.direccion,
  e.telefonos, e.email, e.lugar_nacimiento, e.pais_id, e.estado_id, e.municipio_id, e.parroquia_id,
  e.asignado, e.codigo_opsu, e.fecha_notas, e.codigo_notas, cast(e.fecha_notas AS UNSIGNED) AS fecha_titulo, e.codigo_titulo, e.acta_nacimiento,
  CASE e.periodo_id WHEN 0 THEN NULL ELSE CAST(e.periodo_id AS UNSIGNED) END AS periodo,
  CAST( NULL AS UNSIGNED ) AS carrera,
  CASE e.sede_id WHEN 0 THEN NULL ELSE CAST(e.sede_id AS UNSIGNED) END AS sede,
  e.expediente, e.token, e.usuario_id, e.activo,
  e.created, e.modified, CAST(e.id AS UNSIGNED) AS control
FROM estudiantes e
WHERE DATE(created) = '2024-04-20'
ORDER BY e.id;