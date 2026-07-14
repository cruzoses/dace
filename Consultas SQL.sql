Obtener mallas duplicadas

SELECT programa_id, trayecto_id, asignatura_id, COUNT(*) AS total
FROM mallas
GROUP BY programa_id, trayecto_id, asignatura_id
HAVING COUNT(*) > 1;

SELECT id, programa_id, trayecto_id, asignatura_id
FROM mallas
WHERE (programa_id, trayecto_id, asignatura_id) IN (
    SELECT programa_id, trayecto_id, asignatura_id
    FROM mallas
    GROUP BY programa_id, trayecto_id, asignatura_id
    HAVING COUNT(*) > 1
)
ORDER BY programa_id, trayecto_id, asignatura_id;