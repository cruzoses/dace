SELECT e.id, e.cedula, e.nombres, e.apellidos, ep.periodo_id, ep.sede_id
FROM estudiantes e
LEFT JOIN estudiante_programas ep
    ON ep.estudiante_id = e.id
    AND ep.id = (
        SELECT ep2.id
        FROM estudiante_programas ep2
        WHERE ep2.estudiante_id = e.id
        ORDER BY ep2.created ASC, ep2.id ASC
        LIMIT 1
    );