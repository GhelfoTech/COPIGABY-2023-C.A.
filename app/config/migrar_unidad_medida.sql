ALTER TABLE `producto_insumo`
ADD COLUMN `codigo_media` INT(11) DEFAULT NULL COMMENT 'Unidad de medida base del producto' AFTER `descripcion`,
ADD CONSTRAINT `fk_producto_unidad` FOREIGN KEY (`codigo_media`) REFERENCES `unidad_medida` (`codigo_media`) ON DELETE SET NULL ON UPDATE CASCADE;
