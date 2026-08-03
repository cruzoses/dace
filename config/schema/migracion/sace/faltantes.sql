SET max_recursive_iterations = 100000;
-- Opcion 1
SELECT a.id + 1 AS id_faltante
FROM contenido_cursos a
LEFT JOIN contenido_cursos b ON b.id = a.id + 1
WHERE b.id IS NULL AND a.id < (SELECT MAX(id) FROM contenido_cursos)
ORDER BY a.id;
-- Opcion 2
WITH RECURSIVE secuencia AS (
    -- 1. Buscamos el punto de inicio y el punto final
    SELECT MIN(id) AS id_esperado, MAX(id) AS id_maximo FROM contenido_cursos
    
    UNION ALL
    
    -- 2. Generamos los números intermedios de uno en uno
    SELECT id_esperado + 1, id_maximo
    FROM secuencia
    WHERE id_esperado < id_maximo
)
-- 3. Cruzamos la secuencia con la tabla real y nos quedamos con los que falten
SELECT s.id_esperado AS id_eliminado
FROM secuencia s
LEFT JOIN contenido_cursos u ON s.id_esperado = u.id
WHERE u.id IS NULL;
