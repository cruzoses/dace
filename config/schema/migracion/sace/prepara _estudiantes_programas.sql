UPDATE estudiantes SET control = id;
CREATE TABLE IF NOT EXISTS students(SELECT * FROM estudiantes);
CREATE INDEX IF NOT EXISTS idx_students_control ON students(control,id);
CREATE TABLE IF NOT EXISTS students_programs(SELECT * FROM estudiante_programas);
CREATE INDEX IF NOT EXISTS idx_students_programs ON students_programs(id);
UPDATE students_programs sp
INNER JOIN students st ON st.control = sp.estudiante_id
SET sp.estudiante_id = st.id;
