-- MySQL Workbench Synchronization
-- Generated: 2026-07-16 07:44
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Franco

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

ALTER TABLE `biblioflex`.`permisos` 
ADD INDEX `index` () VISIBLE;
;

ALTER TABLE `biblioflex`.`usuario` 
;
ALTER TABLE `biblioflex`.`usuario` RENAME INDEX `FK_usuario_permisos` TO `FK_usuario_permisos_idx`;
ALTER TABLE `biblioflex`.`usuario` ALTER INDEX `FK_usuario_permisos_idx` VISIBLE;

CREATE TABLE IF NOT EXISTS `biblioflex`.`prestamo` (
  `usuario_id` INT(10) UNSIGNED NOT NULL,
  `libro_id` INT(11) NOT NULL,
  `fecha_prestamo` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_estimada` DATE NOT NULL,
  `fecha_devolucion` DATETIME NULL DEFAULT NULL,
  `estado` ENUM('prestado', 'devuelto', 'atrasado') NOT NULL DEFAULT 'prestado',
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  INDEX `idx` (`usuario_id` ASC) VISIBLE,
  INDEX `FK_libro_prestamo_idx` (`libro_id` ASC) VISIBLE,
  CONSTRAINT `fk_usuario_prestamo`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `biblioflex`.`usuario` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_libro_prestamo`
    FOREIGN KEY (`libro_id`)
    REFERENCES `biblioflex`.`libros` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
