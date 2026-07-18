WITH PrimerPrograma AS (
    SELECT 
        estudiante_id, 
        MIN(programa_id) AS min_programa_id
    FROM estudiante_programas
    GROUP BY estudiante_id
),
DetallePrograma AS (
    SELECT 
        ep.estudiante_id,
        ep.programa_id,
        ep.sede_id,
        ep.periodo_id,
        p.carrera_id
    FROM estudiante_programas ep
    INNER JOIN PrimerPrograma pp 
        ON ep.estudiante_id = pp.estudiante_id 
        AND ep.programa_id = pp.min_programa_id
    INNER JOIN programas p 
        ON ep.programa_id = p.id
)
SELECT 
    CAST('V' AS VARCHAR(1)) AS origen, e.cedula, e.nombres, e.apellidos, e.fecha_nacimiento, e.sexo, e.estado_civil,
    e.discapacitado, e.etnia, e.direccion, e.telefonos, e.email, e.lugar_nacimiento, e.pais_id, e.estado_id, e.municipio_id,
    e.parroquia_id, e.asignado, e.codigo_opsu, e.fecha_notas, e.codigo_notas, e.codigo_titulo, CAST(e.codigo_notas AS DATE) AS fecha_titulo,
    e.acta_nacimiento,    
    IFNULL(CAST(dp.periodo_id AS UNSIGNED),151) AS periodo,   -- Saldrá NULL si no tiene programa
    IFNULL(CAST(dp.carrera_id AS UNSIGNED),1) AS carrera,    -- Saldrá NULL si no tiene programa
    IFNULL(CAST(dp.sede_id AS UNSIGNED),1) AS sede,      -- Saldrá NULL si no tiene programa
    e.expediente, e.token, e.usuario_id, e.activo, e.created, e.modified
    /*dp.programa_id,  -- Saldrá NULL si no tiene programa    */
FROM estudiantes e
LEFT JOIN DetallePrograma dp ON e.id = dp.estudiante_id -- <- El cambio clave está aquí
ORDER BY e.id;