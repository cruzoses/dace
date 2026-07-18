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