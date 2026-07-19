SELECT 1 AS sede_id,
CASE 
  WHEN YEAR(created) = 2024 THEN 151
  WHEN YEAR(created) = 2025 THEN 159
  WHEN YEAR(created) = 2026 THEN 164
END AS periodo_id, codigo, dia, turno, desde, hasta, activo, created, modified
FROM horarios
ORDER BY id