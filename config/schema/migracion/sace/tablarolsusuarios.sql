CREATE TABLE IF NOT EXISTS rols_usuarios(
    SELECT rol_id, id AS usuario_id FROM usuarios ORDER BY id
);