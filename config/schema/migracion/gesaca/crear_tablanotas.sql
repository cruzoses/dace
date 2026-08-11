-- Table tablanotas

CREATE TABLE IF NOT EXISTS `tablanotas`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `estudiante_id` Int NOT NULL,
  `periodo_id` Int NOT NULL,
  `asignatura_id` Int NOT NULL,
  `calificacion` Varchar(20) NOT NULL,
  `seccion` Varchar(10) NOT NULL,
  `responsable` Varchar(50) NOT NULL,
  `created` Datetime,
  `modified` Datetime,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
;

CREATE INDEX `IX_Historial_Estudiante` ON `historicos` (`estudiante_id`)
;

CREATE INDEX `IX_Historial_Periodo` ON `historicos` (`periodo_id`)
;

CREATE INDEX `IX_HIstorial_Asignatura` ON `historicos` (`asignatura_id`)
;