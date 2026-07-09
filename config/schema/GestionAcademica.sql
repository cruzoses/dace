/*
Created: 22/6/2026
Modified: 9/7/2026
Model: GestionAcademica
Database: MySQL 8.0
*/

-- Create tables section -------------------------------------------------

-- Table actos

CREATE TABLE IF NOT EXISTS `actos`
(
  `id` Int(11) NOT NULL AUTO_INCREMENT,
  `nombre` Varchar(100) NOT NULL,
  `cohorte` Varchar(20) NOT NULL,
  `lapso` Smallint(6) NOT NULL,
  `fecha` Date NOT NULL,
  `activo` Bool NOT NULL DEFAULT 1,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
)
;

-- Table firmas

CREATE TABLE IF NOT EXISTS `firmas`
(
  `id` Int(11) NOT NULL AUTO_INCREMENT,
  `codigo` Varchar(20) NOT NULL,
  `nombres` Varchar(50) NOT NULL,
  `datos` Text NOT NULL,
  `texto` Text NOT NULL,
  `lugar` Varchar(50) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
)
;

-- Table horarios

CREATE TABLE IF NOT EXISTS `horarios`
(
  `id` Int(11) NOT NULL AUTO_INCREMENT,
  `sede_id` Int NOT NULL,
  `periodo_id` Int NOT NULL,
  `codigo` Varchar(20) NOT NULL,
  `dia` Smallint(6) NOT NULL,
  `turno` Smallint(6) NOT NULL,
  `desde` Varchar(20) NOT NULL,
  `hasta` Varchar(20) NOT NULL,
  `activo` Bool NOT NULL DEFAULT 1,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
)
;

CREATE INDEX `IX_Relationship3` ON `horarios` (`periodo_id`)
;

-- Table aulas

CREATE TABLE IF NOT EXISTS `aulas`
(
  `id` Int(11) NOT NULL AUTO_INCREMENT,
  `sede_id` Int NOT NULL,
  `codigo` Varchar(20) NOT NULL,
  `nombre` Varchar(80) NOT NULL,
  `capacidad` Smallint(6) NOT NULL,
  `ubicacion` Varchar(80) NOT NULL,
  `condicion` Bool NOT NULL DEFAULT 1,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
)
;

-- Table departamentos

CREATE TABLE IF NOT EXISTS `departamentos`
(
  `id` Int(11) NOT NULL AUTO_INCREMENT,
  `nombre` Varchar(200) NOT NULL,
  `responsable` Varchar(50) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
)
;
INSERT INTO departamentos(nombre,responsable,created,modified) VALUES('SIN DEFINIR','SIN DEFINIR',now(),now());

-- Table noticias

CREATE TABLE IF NOT EXISTS `noticias`
(
  `id` Int(11) NOT NULL AUTO_INCREMENT,
  `fecha` Date NOT NULL,
  `titulo` Varchar(100) NOT NULL,
  `contenido` Text NOT NULL,
  `usuario_id` Int NOT NULL,
  `activa` Bool NOT NULL DEFAULT 1,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
)
;

CREATE INDEX `IX_Noticia_Usuario` ON `noticias` (`usuario_id`)
;

-- Table rols

CREATE TABLE IF NOT EXISTS `rols`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `nombre` Varchar(50) NOT NULL,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;
INSERT INTO rols(nombre,activo,created,modified) VALUES('ADMINISTRADOR',1,now(),now());
INSERT INTO rols(nombre,activo,created,modified) VALUES('DIRECTOR C.A.S.R.C.E',1,now(),now());
INSERT INTO rols(nombre,activo,created,modified) VALUES('ANALISTA',1,now(),now());
INSERT INTO rols(nombre,activo,created,modified) VALUES('COORDINADOR DE SEDE',1,now(),now());
INSERT INTO rols(nombre,activo,created,modified) VALUES('DOCENTE',1,now(),now());
INSERT INTO rols(nombre,activo,created,modified) VALUES('COORDINADOR P.N.F.A',1,now(),now());
INSERT INTO rols(nombre,activo,created,modified) VALUES('ANALISTA P.N.F.A',1,now(),now());
INSERT INTO rols(nombre,activo,created,modified) VALUES('AUXILIAR',1,now(),now());
INSERT INTO rols(nombre,activo,created,modified) VALUES('ESTUDIANTE',1,now(),now());
INSERT INTO rols(nombre,activo,created,modified) VALUES('REVISION',1,now(),now());

-- Table usuarios

CREATE TABLE IF NOT EXISTS `usuarios`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `cedula` Int NOT NULL,
  `nombres` Varchar(50) NOT NULL,
  `apellidos` Varchar(50) NOT NULL,
  `fecha_nacimiento` Date NOT NULL,
  `sexo` Char(1) NOT NULL,
  `email` Varchar(50) NOT NULL,
  `telefonos` Varchar(50),
  `username` Varchar(50) NOT NULL,
  `password` Varchar(128) NOT NULL,
  `twitter` Varchar(40),
  `instagram` Varchar(40),
  `facebook` Varchar(40),
  `foto` Varchar(50),
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;
INSERT INTO usuarios (cedula, nombres, apellidos, fecha_nacimiento, sexo, email, telefonos, username, password, activo, created, modified) VALUES (1, 'OTIC', 'UPTBAL', '2010-05-12', 'M', 'oticuptbal@gmail.com', '0234-323.68.40', 'admin', '$2y$10$E01ddNCJAse.B7UO4mnKBemyK464hEvWGSDwGVHNqQPB7vTfteeVW', 1, now(), now());

-- Table rols_usuarios

CREATE TABLE IF NOT EXISTS `rols_usuarios`
(
  `rol_id` Int NOT NULL,
  `usuario_id` Int NOT NULL
) ENGINE = InnoDB
;
INSERT INTO rols_usuarios(rol_id,usuario_id) VALUES(1,1);

ALTER TABLE `rols_usuarios` ADD PRIMARY KEY (`rol_id`, `usuario_id`)
;

-- Table auditorias

CREATE TABLE IF NOT EXISTS `auditorias`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `usuario_id` Int NOT NULL,
  `fecha` Datetime NOT NULL,
  `evento` Varchar(40) NOT NULL,
  `detalle` Text NOT NULL,
  `host` Varchar(50) NOT NULL,
  `agente` Varchar(200) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Usuario_Auditoria` ON `auditorias` (`usuario_id`)
;

-- Table mension_carreras

CREATE TABLE IF NOT EXISTS `mension_carreras`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `nombre` Varchar(80) NOT NULL,
  `activa` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;
INSERT INTO mension_carreras(nombre,activa,created,modified) values('SIN MENSION',1,now(),now());

-- Table sedes

CREATE TABLE IF NOT EXISTS `sedes`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `codigo` Varchar(10) NOT NULL,
  `nombre` Varchar(80) NOT NULL,
  `direccion` Text NOT NULL,
  `telefonos` Varchar(40),
  `responsable` Varchar(50) NOT NULL,
  `principal` Tinyint(1) NOT NULL,
  `activa` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

-- Table carreras

CREATE TABLE IF NOT EXISTS `carreras`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `codigo` Varchar(20) NOT NULL,
  `nombre` Varchar(80) NOT NULL,
  `mension_carrera_id` Int NOT NULL,
  `titulo_otorgado` Varchar(80) NOT NULL,
  `activa` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;
INSERT INTO carreras(codigo,nombre,mension_carrera_id,titulo_otorgado,activa,created,modified) VALUES('S/C','SIN DEFINIR',1,'SIN DEFINIR',1,now(),now());

CREATE INDEX `IX_Carrera_Mension` ON `carreras` (`mension_carrera_id`)
;

-- Table sedes_carreras

CREATE TABLE `sedes_carreras`
(
  `sede_id` Int NOT NULL,
  `carrera_id` Int NOT NULL
)
;

ALTER TABLE `sedes_carreras` ADD PRIMARY KEY (`sede_id`, `carrera_id`)
;

-- Table subsistemas

CREATE TABLE `subsistemas`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `codigo` Varchar(20) NOT NULL,
  `nombre` Varchar(50) NOT NULL,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
)
;
INSERT INTO subsistemas(codigo,nombre,activo,created,modified) VALUES('PREG','PRE-GRADO',1,now(),now());
INSERT INTO subsistemas(codigo,nombre,activo,created,modified) VALUES('POST','POST-GRADO',1,now(),now());

-- Table programas

CREATE TABLE IF NOT EXISTS `programas`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `codigo` Varchar(20) NOT NULL,
  `nombre` Varchar(80) NOT NULL,
  `carrera_id` Int NOT NULL,
  `subsistema_id` Int NOT NULL,
  `nota_minima` Smallint(6) NOT NULL,
  `creditos` Smallint(6) NOT NULL,
  `pasantia` Tinyint(1) NOT NULL,
  `califica` Tinyint(1) NOT NULL,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Programa_Carrera` ON `programas` (`carrera_id`)
;

CREATE INDEX `IX_Programa_Sistema` ON `programas` (`subsistema_id`)
;

-- Table estudiantes

CREATE TABLE IF NOT EXISTS `estudiantes`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `origen` Char(1) NOT NULL,
  `cedula` Int NOT NULL,
  `nombres` Varchar(50) NOT NULL,
  `apellidos` Varchar(50) NOT NULL,
  `fecha_nacimiento` Date NOT NULL,
  `sexo` Char(1) NOT NULL,
  `estado_civil` Char(1) NOT NULL,
  `discapacitado` Tinyint(1) NOT NULL,
  `etnia` Tinyint(1) NOT NULL,
  `direccion` Text NOT NULL,
  `telefonos` Varchar(50),
  `email` Varchar(50) NOT NULL,
  `lugar_nacimiento` Text,
  `pais_id` Int,
  `estado_id` Int,
  `municipio_id` Int,
  `parroquia_id` Int,
  `asignado` Tinyint(1),
  `codigo_opsu` Varchar(20),
  `fecha_notas` Date,
  `codigo_notas` Varchar(20),
  `fecha_titulo` Date,
  `codigo_titulo` Varchar(20),
  `acta_nacimiento` Varchar(20),
  `periodo` Int NOT NULL,
  `carrera` Int NOT NULL,
  `sede` Int NOT NULL,
  `expediente` Varchar(20) NOT NULL,
  `token` Varchar(10),
  `usuario_id` Int,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Estudiante_Pais` ON `estudiantes` (`pais_id`)
;

CREATE INDEX `IX_Estudiante_Estado` ON `estudiantes` (`estado_id`)
;

CREATE INDEX `IX_Estudiante_Municipio` ON `estudiantes` (`municipio_id`)
;

CREATE INDEX `IX_Estudiante_Parroquia` ON `estudiantes` (`parroquia_id`)
;

CREATE INDEX `IX_Estudiante_Usuario` ON `estudiantes` (`usuario_id`)
;

-- Table paises

CREATE TABLE IF NOT EXISTS `paises`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `nombre` Varchar(50) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;
INSERT INTO paises(nombre,created,modified) values('VENEZUELA',now(),now());

-- Table estados

CREATE TABLE IF NOT EXISTS `estados`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `pais_id` Int NOT NULL,
  `nombre` Varchar(50) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Estado_Pais` ON `estados` (`pais_id`)
;

-- Table municipios

CREATE TABLE IF NOT EXISTS `municipios`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `estado_id` Int NOT NULL,
  `nombre` Varchar(50) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Municipio_Estado` ON `municipios` (`estado_id`)
;

-- Table parroquias

CREATE TABLE IF NOT EXISTS `parroquias`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `municipio_id` Int NOT NULL,
  `nombre` Varchar(50) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Parroquia_Municipio` ON `parroquias` (`municipio_id`)
;

-- Table trayectos

CREATE TABLE `trayectos`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `codigo` Varchar(20) NOT NULL,
  `nombre` Varchar(50) NOT NULL,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
)
;

-- Table empleados

CREATE TABLE IF NOT EXISTS `empleados`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `cedula` Int NOT NULL,
  `nombres` Varchar(50) NOT NULL,
  `apellidos` Varchar(50) NOT NULL,
  `fecha_nacimiento` Date NOT NULL,
  `sexo` Char(1) NOT NULL,
  `email` Varchar(50) NOT NULL,
  `telefonos` Varchar(50),
  `token` Varchar(10) NOT NULL,
  `usuario_id` Int,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Empleado_Usuario` ON `empleados` (`usuario_id`)
;

-- Table grupo_asignaturas

CREATE TABLE `grupo_asignaturas`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `codigo` Varchar(20) NOT NULL,
  `nombre` Varchar(50) NOT NULL,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
)
;
INSERT INTO grupo_asignaturas(codigo,nombre,activo,created,modified) VALUES('N/A','SIN GRUPO',1,now(),now());

-- Table asignaturas

CREATE TABLE `asignaturas`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `codigo` Varchar(20) NOT NULL,
  `nombre` Varchar(150) NOT NULL,
  `horas_teoricas` Smallint(6) NOT NULL,
  `horas_practicas` Smallint(6) NOT NULL,
  `frecuencia` Smallint(6) NOT NULL,
  `calificacion` Smallint(6) NOT NULL,
  `creditos` Smallint(6) NOT NULL,
  `costo` Double NOT NULL,
  `requisitos` Text,
  `convalidacion` Text,
  `grupo_asignatura_id` Int NOT NULL,
  `activa` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
)
;

CREATE INDEX `IX_Asignatura_grupo` ON `asignaturas` (`grupo_asignatura_id`)
;

-- Table mallas

CREATE TABLE IF NOT EXISTS `mallas`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `carrera_id` Int NOT NULL,
  `programa_id` Int NOT NULL,
  `trayecto_id` Int NOT NULL,
  `asignatura_id` Int NOT NULL,
  `nota_minima` Smallint(6),
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Malla_Programa` ON `mallas` (`programa_id`)
;

CREATE INDEX `IX_Malla_Asignatura` ON `mallas` (`asignatura_id`)
;

CREATE INDEX `IX_Malla_Trayecto` ON `mallas` (`trayecto_id`)
;

CREATE INDEX `IX_Malla_Carrera` ON `mallas` (`carrera_id`)
;

-- Table cursos

CREATE TABLE IF NOT EXISTS `cursos`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `sede_id` Int NOT NULL,
  `periodo_id` Int NOT NULL,
  `carrera_id` Int NOT NULL,
  `trayecto_id` Int NOT NULL,
  `programas` Varchar(40) NOT NULL,
  `asignatura_id` Int NOT NULL,
  `profesores` Varchar(40) NOT NULL,
  `docente_id` Int NOT NULL,
  `seccion` Varchar(20) NOT NULL,
  `cupos` Smallint(6) NOT NULL,
  `aula_id` Int(11) NOT NULL,
  `horario` Varchar(60) NOT NULL,
  `cerrado` Tinyint(1) NOT NULL,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Curso_Sede` ON `cursos` (`sede_id`)
;

CREATE INDEX `IX_Curso_Periodo` ON `cursos` (`periodo_id`)
;

CREATE INDEX `IX_Curso_Carrera` ON `cursos` (`carrera_id`)
;

CREATE INDEX `IX_Curso_Docente` ON `cursos` (`docente_id`)
;

CREATE INDEX `IX_Curso_Trayecto` ON `cursos` (`trayecto_id`)
;

CREATE INDEX `IX_Curso_Aula` ON `cursos` (`aula_id`)
;

CREATE INDEX `IX_Curso_Asignatura` ON `cursos` (`asignatura_id`)
;

-- Table periodos

CREATE TABLE `periodos`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `codigo` Varchar(20) NOT NULL,
  `nombre` Varchar(50) NOT NULL,
  `lapso` Smallint(6) NOT NULL,
  `nota_minima` Smallint(6) NOT NULL,
  `inicio` Date NOT NULL,
  `cierre` Date NOT NULL,
  `califica` Tinyint(1) NOT NULL,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
)
;

-- Table docentes

CREATE TABLE `docentes`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `cedula` Int NOT NULL,
  `nombres` Varchar(50) NOT NULL,
  `apellidos` Varchar(50) NOT NULL,
  `fecha_nacimiento` Date NOT NULL,
  `sexo` Char(1) NOT NULL,
  `email` Varchar(50) NOT NULL,
  `telefonos` Varchar(50),
  `departamento_id` Int(11),
  `token` Varchar(10) NOT NULL,
  `usuario_id` Int,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
)
;

CREATE INDEX `IX_Docente_Usuario` ON `docentes` (`usuario_id`)
;

CREATE INDEX `IX_Docente_Departamento` ON `docentes` (`departamento_id`)
;

-- Table indicador_cursos

CREATE TABLE IF NOT EXISTS `indicador_cursos`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `curso_id` Int NOT NULL,
  `indicador_id` Int NOT NULL,
  `desde` Date NOT NULL,
  `hasta` Date NOT NULL,
  `escala_nota` Smallint(6) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Indicador_Curso` ON `indicador_cursos` (`curso_id`)
;

CREATE INDEX `IX_Curso_Indicador` ON `indicador_cursos` (`indicador_id`)
;

-- Table contenidos_cursos

CREATE TABLE IF NOT EXISTS `contenidos_cursos`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `fecha` Date NOT NULL,
  `descripcion` Varchar(50) NOT NULL,
  `detalle` Text NOT NULL,
  `ponderacion` Smallint(6) NOT NULL,
  `indicador_curso_id` Int NOT NULL,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Cursos_Indicadores` ON `contenidos_cursos` (`indicador_curso_id`)
;

-- Table estudiante_programas

CREATE TABLE IF NOT EXISTS `estudiante_programas`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `estudiante_id` Int NOT NULL,
  `carrera_id` Int NOT NULL,
  `programa_id` Int NOT NULL,
  `sede_id` Int NOT NULL,
  `fecha_egreso` Date,
  `cohorte` Varchar(20),
  `indice` Double,
  `culminado` Tinyint(1) NOT NULL,
  `observacion` Text,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Programa_Estudiante` ON `estudiante_programas` (`estudiante_id`)
;

CREATE INDEX `IX_Estudiante_Programa` ON `estudiante_programas` (`programa_id`)
;

CREATE INDEX `IX_Estudiante_Sede` ON `estudiante_programas` (`sede_id`)
;

CREATE INDEX `IX_Estudiante_Carrera` ON `estudiante_programas` (`carrera_id`)
;

-- Table notas_cursos

CREATE TABLE IF NOT EXISTS `notas_cursos`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `contenido_curso_id` Int NOT NULL,
  `estudiante_id` Int NOT NULL,
  `calificacion` Varchar(10),
  `responsable` Varchar(50),
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Calificaciones_Estudiantes` ON `notas_cursos` (`estudiante_id`)
;

CREATE INDEX `IX_Evaluaciones_Contenidos` ON `notas_cursos` (`contenido_curso_id`)
;

-- Table indicadores

CREATE TABLE IF NOT EXISTS `indicadores`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `nombre` Varchar(50) NOT NULL,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

-- Table estudiante_cursos

CREATE TABLE IF NOT EXISTS `estudiante_cursos`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `curso_id` Int NOT NULL,
  `estudiante_id` Int NOT NULL,
  `calificacion` Varchar(10),
  `recuperacion` Varchar(10),
  `definitiva` Varchar(20),
  `responsable` Varchar(50) NOT NULL,
  `observacion` Text,
  `activo` Tinyint(1) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Estudiantes_Cursos` ON `estudiante_cursos` (`curso_id`)
;

CREATE INDEX `IX_Curso_Estudiante` ON `estudiante_cursos` (`estudiante_id`)
;

-- Table historicos

CREATE TABLE IF NOT EXISTS `historicos`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `estudiante_id` Int NOT NULL,
  `periodo_id` Int NOT NULL,
  `asignatura_id` Int NOT NULL,
  `calificacion` Varchar(10) NOT NULL,
  `seccion` Varchar(10) NOT NULL,
  `responsable` Varchar(50) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Historico_Estudiante` ON `historicos` (`estudiante_id`)
;

CREATE INDEX `IX_Historico_Periodo` ON `historicos` (`periodo_id`)
;

CREATE INDEX `IX_HIstorico_Asignatura` ON `historicos` (`asignatura_id`)
;

-- Table graduandos

CREATE TABLE IF NOT EXISTS `graduandos`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `institucion` Smallint(6) NOT NULL,
  `acto_id` Int(11) NOT NULL,
  `estudiante_id` Int,
  `indice` Double NOT NULL,
  `control` Varchar(10),
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Graduandos_Actos` ON `graduandos` (`acto_id`)
;

CREATE INDEX `IX_Grado_Estudiante` ON `graduandos` (`estudiante_id`)
;

-- Table situacion_estudiantes

CREATE TABLE IF NOT EXISTS `situacion_estudiantes`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `estudiante_id` Int NOT NULL,
  `programa_id` Int NOT NULL,
  `asignatura_id` Int NOT NULL,
  `periodo_id` Int NOT NULL,
  `seccion` Varchar(20),
  `calificacion` Varchar(5),
  `responsable` Varchar(50),
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Situacion_Academica` ON `situacion_estudiantes` (`estudiante_id`)
;

CREATE INDEX `IX_Situacion_Programa` ON `situacion_estudiantes` (`programa_id`)
;

CREATE INDEX `IX_Situacion_Asignatura` ON `situacion_estudiantes` (`asignatura_id`)
;

CREATE INDEX `IX_Situacion_Periodo` ON `situacion_estudiantes` (`periodo_id`)
;

-- Create foreign keys (relationships) section -------------------------------------------------

ALTER TABLE `rols_usuarios` ADD CONSTRAINT `pfk_rol_usuario` FOREIGN KEY (`rol_id`) REFERENCES `rols` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `rols_usuarios` ADD CONSTRAINT `pfk_usuario_rol` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `auditorias` ADD CONSTRAINT `pfk_auditoria_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `sedes_carreras` ADD CONSTRAINT `pfk_sede_carrera` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `sedes_carreras` ADD CONSTRAINT `pfk_carrera_sede` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `carreras` ADD CONSTRAINT `pfk_carrera_mension_carrera` FOREIGN KEY (`mension_carrera_id`) REFERENCES `mension_carreras` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `programas` ADD CONSTRAINT `pfk_carrera_programa` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `programas` ADD CONSTRAINT `pfk_subsistema_programa` FOREIGN KEY (`subsistema_id`) REFERENCES `subsistemas` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `estudiantes` ADD CONSTRAINT `pfk_pais_estudiante` FOREIGN KEY (`pais_id`) REFERENCES `paises` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `estados` ADD CONSTRAINT `pfk_pais_estado` FOREIGN KEY (`pais_id`) REFERENCES `paises` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `estudiantes` ADD CONSTRAINT `pfk_estado_estudiante` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `municipios` ADD CONSTRAINT `pfk_estado_municipio` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `estudiantes` ADD CONSTRAINT `pfk_municipio_estudiante` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `parroquias` ADD CONSTRAINT `pfk_municipio_parroquia` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `estudiantes` ADD CONSTRAINT `pfk_parroquia_estudiante` FOREIGN KEY (`parroquia_id`) REFERENCES `parroquias` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `empleados` ADD CONSTRAINT `pfk_usuario_empleado` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `estudiantes` ADD CONSTRAINT `pfk_usuario_estudiante` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `asignaturas` ADD CONSTRAINT `pfk_grupo_asignatura` FOREIGN KEY (`grupo_asignatura_id`) REFERENCES `grupo_asignaturas` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `mallas` ADD CONSTRAINT `pfk_programa_malla` FOREIGN KEY (`programa_id`) REFERENCES `programas` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `mallas` ADD CONSTRAINT `pfk_asignatura_malla` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `cursos` ADD CONSTRAINT `pfk_sede_curso` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `cursos` ADD CONSTRAINT `pfk_periodo_curso` FOREIGN KEY (`periodo_id`) REFERENCES `periodos` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `cursos` ADD CONSTRAINT `pfk_carrera_curso` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `docentes` ADD CONSTRAINT `pfk_usuario_docente` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `cursos` ADD CONSTRAINT `pfk_docente_curso` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `cursos` ADD CONSTRAINT `pfk_trayecto_curso` FOREIGN KEY (`trayecto_id`) REFERENCES `trayectos` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `estudiante_programas` ADD CONSTRAINT `pfk_estudiante_programa` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `estudiante_programas` ADD CONSTRAINT `pfk_programa_estudiante` FOREIGN KEY (`programa_id`) REFERENCES `programas` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `indicador_cursos` ADD CONSTRAINT `pfk_curso_indicador` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `noticias` ADD CONSTRAINT `pfk_usuario_noticia` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `indicador_cursos` ADD CONSTRAINT `pfk_indicador_curso` FOREIGN KEY (`indicador_id`) REFERENCES `indicadores` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `contenidos_cursos` ADD CONSTRAINT `pfk_indicadores_cursos` FOREIGN KEY (`indicador_curso_id`) REFERENCES `indicador_cursos` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `estudiante_cursos` ADD CONSTRAINT `pfk_curso_estudiante` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `estudiante_cursos` ADD CONSTRAINT `pfk_estudiante_curso` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `notas_cursos` ADD CONSTRAINT `pfk_estudiante_calificaciones` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `notas_cursos` ADD CONSTRAINT `pfk_evaluacion_contenidos` FOREIGN KEY (`contenido_curso_id`) REFERENCES `contenidos_cursos` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `historicos` ADD CONSTRAINT `pfk_estudiante_historico` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `historicos` ADD CONSTRAINT `pfk_periodo_historico` FOREIGN KEY (`periodo_id`) REFERENCES `periodos` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `historicos` ADD CONSTRAINT `pfk_asignatura_historico` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `graduandos` ADD CONSTRAINT `pfk_acto_graduandos` FOREIGN KEY (`acto_id`) REFERENCES `actos` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `situacion_estudiantes` ADD CONSTRAINT `pfk_estudiante_situacion` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `graduandos` ADD CONSTRAINT `pfk_Estudiante_grado` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `estudiante_programas` ADD CONSTRAINT `pfk_sede_estudiante` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `docentes` ADD CONSTRAINT `pfk_departamento_docente` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `cursos` ADD CONSTRAINT `pfk_aula_curso` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `aulas` ADD CONSTRAINT `pfk_sede_aula` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `horarios` ADD CONSTRAINT `pfk_sede_horario` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `horarios` ADD CONSTRAINT `pfk_periodo_horario` FOREIGN KEY (`periodo_id`) REFERENCES `periodos` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `mallas` ADD CONSTRAINT `pfk_trayecto_malla` FOREIGN KEY (`trayecto_id`) REFERENCES `trayectos` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `cursos` ADD CONSTRAINT `pfk_asignatura_curso` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `mallas` ADD CONSTRAINT `pfk_carrera_malla` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `estudiante_programas` ADD CONSTRAINT `pfk_carrera_estudiante` FOREIGN KEY (`carrera_id`) REFERENCES `carreras` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `situacion_estudiantes` ADD CONSTRAINT `pfk_programa_situacion` FOREIGN KEY (`programa_id`) REFERENCES `programas` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `situacion_estudiantes` ADD CONSTRAINT `pfk_asignatura_situacion` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

ALTER TABLE `situacion_estudiantes` ADD CONSTRAINT `pfk_periodo_situacion` FOREIGN KEY (`periodo_id`) REFERENCES `periodos` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION
;

