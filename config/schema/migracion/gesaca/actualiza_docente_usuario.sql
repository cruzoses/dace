UPDATE docentes d
INNER JOIN usuarios u ON u.cedula = d.cedula
SET d.usuario_id = u.id;
/*
UPDATE docentes d
INNER JOIN usuarios u ON u.cedula = d.cedula
SET d.usuario_id = u.id
WHERE d.usuario_id IS NULL OR d.usuario_id != u.id;
*/