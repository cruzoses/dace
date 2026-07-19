SELECT 
    res.asignatura_id,
    a.codigo,
    a.nombre AS nombre_asignatura,
    tot.veces_cursada,
    tot.suma_calificaciones,
    n.calificacion AS ultima_calificacion,
    n.periodo_id AS ultimo_periodo,
    n.seccion AS ultima_seccion,
    n.responsable AS ultimo_responsable
FROM (
    SELECT asignatura_id, MAX(periodo_id) AS max_periodo
    FROM tablanotas
    WHERE estudiante_id = 17913
    GROUP BY asignatura_id
) res
INNER JOIN tablanotas n 
    ON n.asignatura_id = res.asignatura_id 
    AND n.periodo_id = res.max_periodo
    AND n.estudiante_id = 17913
INNER JOIN (
    SELECT 
        asignatura_id,
        COUNT(*) AS veces_cursada,
        SUM(CASE 
            WHEN UPPER(TRIM(calificacion)) = 'A' THEN 20
            WHEN UPPER(TRIM(calificacion)) = 'R' THEN 0
            ELSE CAST(calificacion AS UNSIGNED)
        END) AS suma_calificaciones
    FROM tablanotas
    WHERE estudiante_id = 17913
    GROUP BY asignatura_id
) tot ON tot.asignatura_id = res.asignatura_id
INNER JOIN asignaturas a ON a.id = res.asignatura_id
ORDER BY res.asignatura_id;