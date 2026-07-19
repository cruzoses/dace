Obtener mallas duplicadas
Opcion 1
SELECT programa_id, trayecto_id, asignatura_id, COUNT(*) AS total
FROM mallas
GROUP BY programa_id, trayecto_id, asignatura_id
HAVING COUNT(*) > 1;
Opcion 2
SELECT id, programa_id, trayecto_id, asignatura_id
FROM mallas
WHERE (programa_id, trayecto_id, asignatura_id) IN (
    SELECT programa_id, trayecto_id, asignatura_id
    FROM mallas
    GROUP BY programa_id, trayecto_id, asignatura_id
    HAVING COUNT(*) > 1
)
ORDER BY programa_id, trayecto_id, asignatura_id;

Obtener ids faltantes
Opcion 1
SELECT a.id + 1 AS id_faltante
FROM periodos a
LEFT JOIN periodos b ON b.id = a.id + 1
WHERE b.id IS NULL AND a.id < (SELECT MAX(id) FROM periodos)
ORDER BY a.id;
Opcion 2
WITH RECURSIVE secuencia AS (
    -- 1. Buscamos el punto de inicio y el punto final
    SELECT MIN(id) AS id_esperado, MAX(id) AS id_maximo FROM usuarios
    
    UNION ALL
    
    -- 2. Generamos los números intermedios de uno en uno
    SELECT id_esperado + 1, id_maximo
    FROM secuencia
    WHERE id_esperado < id_maximo
)
-- 3. Cruzamos la secuencia con la tabla real y nos quedamos con los que falten
SELECT s.id_esperado AS id_eliminado
FROM secuencia s
LEFT JOIN usuarios u ON s.id_esperado = u.id
WHERE u.id IS NULL;

Si entre tu ID mínimo y tu ID máximo hay una diferencia de más de 1,000 números, la consulta te dará un error. 
Para solucionarlo, ejecuta esto justo antes en la misma sesión para ampliar el límite (por ejemplo, a un millón):

SET max_recursive_iterations = 1000000;

IFNULL(token, 'A1B2C3d4') AS token
-- O también: COALESCE(token, 'A1B2C3d4') AS token

Extraer notas del Historico
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