WITH RECURSIVE secuencia AS (
    -- 1. Buscamos el punto de inicio y el punto final
    SELECT MIN(id) AS id_esperado, MAX(id) AS id_maximo FROM estudiantes
    
    UNION ALL
    
    -- 2. Generamos los números intermedios de uno en uno
    SELECT id_esperado + 1, id_maximo
    FROM secuencia
    WHERE id_esperado < id_maximo
)
-- 3. Cruzamos la secuencia con la tabla real y nos quedamos con los que falten
SELECT s.id_esperado AS id_eliminado
FROM secuencia s
LEFT JOIN estudiantes u ON s.id_esperado = u.id
WHERE u.id IS NULL;