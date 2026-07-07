SELECT CAST('V' AS CHAR(1) ) AS origen, e.cedula, e.nombres, e.apellidos, e.fecha_nacimiento, e.sexo, e.estado_civil, e.discapacitado, e.etnia, e.direccion,
  e.telefonos, e.email, e.lugar_nacimiento, e.pais_id, e.estado_id, e.municipio_id, e.parroquia_id,
  e.asignado, e.codigo_opsu, e.fecha_notas, e.codigo_notas, cast(e.fecha_notas AS UNSIGNED) AS fecha_titulo, e.codigo_titulo, e.acta_nacimiento,
  CAST(ep.periodo_id AS UNSIGNED )AS periodo, CAST(p.carrera_id AS UNSIGNED) AS carrera, CAST(ep.sede_id AS UNSIGNED) AS sede,
  e.expediente, e.token, e.usuario_id, e.activo,
  e.created, e.modified, CAST(e.id AS UNSIGNED) AS estudiante_id
FROM estudiantes e
LEFT JOIN (
    SELECT ep.*,
           ROW_NUMBER() OVER (PARTITION BY ep.estudiante_id ORDER BY ep.created ASC, ep.id ASC) AS rn
    FROM estudiante_programas ep
) ep ON ep.estudiante_id = e.id AND ep.rn = 1
LEFT JOIN programas p ON p.id = ep.programa_id
ORDER BY e.id;