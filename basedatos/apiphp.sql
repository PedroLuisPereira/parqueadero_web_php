CREATE DATABASE `parqueadero`;
use `parqueadero`;

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_documento` VARCHAR(50) NOT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  `apellidos` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_documento` (`numero_documento`)
)COLLATE='utf8mb4_general_ci';


CREATE TABLE `vehiculos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(50) NOT NULL,
  `placa` VARCHAR(50) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `placa` (`placa`),
  FOREIGN KEY (cliente_id) REFERENCES clientes(id)
)COLLATE='utf8mb4_general_ci';

CREATE TABLE `servicios` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `hora_entrada` DATETIME NOT NULL,
    `hora_salida` DATETIME,
    `minutos` int NOT NULL DEFAULT 0,
    `valor_minuto` DOUBLE NOT NULL DEFAULT 0,
    `total` DOUBLE NOT NULL DEFAULT 0,
    `estado` VARCHAR(50) NOT NULL,
    `parqueadero` VARCHAR(50) NOT NULL,
    `vehiculo_id` INT NOT NULL,
     PRIMARY KEY (`id`),
     FOREIGN KEY (vehiculo_id) REFERENCES vehiculos(id)
)
COLLATE='utf8mb4_general_ci';

CREATE TABLE `tarifas` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `minuto_autos` DOUBLE NOT NULL DEFAULT 0,
    `minuto_bicicletas` DOUBLE NOT NULL DEFAULT 0,
    `minuto_motos` DOUBLE NOT NULL DEFAULT 0,
    `descuento` DOUBLE NOT NULL DEFAULT 0,
    `minutos` INT NOT NULL DEFAULT 0,
     PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci';

CREATE TABLE `parqueaderos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `estado` VARCHAR(50) NOT NULL DEFAULT "Disponible",
    `tipo` VARCHAR(50) NOT NULL,
    `parqueadero` VARCHAR(50) NOT NULL,
    `vehiculo_id` INT,
    PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_general_ci';

CREATE TABLE `usuarios` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    `correo` VARCHAR(80) NOT NULL COLLATE 'utf8mb4_general_ci',
    `contra` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    `rol` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
    `estado` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
    `foto` VARCHAR(255)  COLLATE 'utf8mb4_general_ci',
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE INDEX `correo` (`correo`) USING BTREE
)
COLLATE='utf8mb4_general_ci' ENGINE=INNODB;

CREATE TABLE `tokens` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `token` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    `emitido` TIMESTAMP NOT NULL COLLATE 'utf8mb4_general_ci',
    `expiracion` TIMESTAMP NOT NULL COLLATE 'utf8mb4_general_ci',
    `usuario_id` INT(11) NOT NULL COLLATE 'utf8mb4_general_ci',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX `usuario_id` (`usuario_id`) USING BTREE,
    CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB;

/* usuario administrador, contra = 123456 */ 
INSERT INTO `usuarios` (`id`, `nombre`, `correo`,`contra`,`rol`,`estado`)
VALUES ('1', 'Pedro Luis', 'druped@hotmail.com','e10adc3949ba59abbe56e057f20f883e','Administrador','Activo');

/*tarifas*/
INSERT INTO `tarifas` (`id`) VALUES ('1');

/* automoviles*/
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('2', 'Automovil', 'A2');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('1', 'Automovil', 'A1');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('3', 'Automovil', 'A3');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('4', 'Automovil', 'A4');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('5', 'Automovil', 'A5');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('6', 'Automovil', 'A6');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('7', 'Automovil', 'A7');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('8', 'Automovil', 'A8');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('9', 'Automovil', 'A9');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('10', 'Automovil', 'A10');

/*bicicletas*/
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('11', 'Bicicleta', 'B1');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('12', 'Bicicleta', 'B2');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('13', 'Bicicleta', 'B3');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('14', 'Bicicleta', 'B4');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('15', 'Bicicleta', 'B5');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('16', 'Bicicleta', 'B6');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('17', 'Bicicleta', 'B7');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('18', 'Bicicleta', 'B8');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('19', 'Bicicleta', 'B9');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('20', 'Bicicleta', 'B10');

/*motos*/
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('21', 'Moto', 'M1');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('22', 'Moto', 'M2');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('23', 'Moto', 'M3');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('24', 'Moto', 'M4');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('25', 'Moto', 'M5');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('26', 'Moto', 'M6');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('27', 'Moto', 'M7');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('28', 'Moto', 'M8');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('29', 'Moto', 'M9');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('30', 'Moto', 'M10');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('31', 'Moto', 'M11');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('32', 'Moto', 'M12');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('33', 'Moto', 'M13');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('34', 'Moto', 'M14');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('35', 'Moto', 'M15');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('36', 'Moto', 'M16');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('37', 'Moto', 'M17');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('38', 'Moto', 'M18');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('39', 'Moto', 'M19');
INSERT INTO `parqueaderos` (`id`, `tipo`, `parqueadero`) VALUES ('40', 'Moto', 'M20');