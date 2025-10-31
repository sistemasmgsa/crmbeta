ALTER TABLE `oportunidades`
ADD COLUMN `usuario_creacion_id` INT(11) NULL AFTER `etapa`,
ADD CONSTRAINT `fk_oportunidades_usuario_creacion`
FOREIGN KEY (`usuario_creacion_id`)
REFERENCES `usuarios`(`id_usuario`);
