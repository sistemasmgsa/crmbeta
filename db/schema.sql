CREATE TABLE ubigeos (
  id_ubigeo int NOT NULL AUTO_INCREMENT,
  departamento varchar(100) NOT NULL,
  provincia varchar(100) NOT NULL,
  distrito varchar(100) NOT NULL,
  estado int DEFAULT 1,
  PRIMARY KEY (id_ubigeo)
)
ENGINE = INNODB,
AUTO_INCREMENT = 3,
AVG_ROW_LENGTH = 16384,
CREATE TABLE tipos_documento_identidad (
  id_tipo_documento int NOT NULL AUTO_INCREMENT,
  nombre_documento varchar(50) NOT NULL,
  estado int DEFAULT 1,
  PRIMARY KEY (id_tipo_documento)
)
ENGINE = INNODB,
AUTO_INCREMENT = 5,
AVG_ROW_LENGTH = 5461,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
CREATE TABLE clientes (
  id_cliente int NOT NULL AUTO_INCREMENT,
  nombre_cliente varchar(100) NOT NULL,
  direccion_cliente varchar(255) DEFAULT NULL,
  telefono_cliente varchar(20) DEFAULT NULL,
  website_cliente varchar(100) DEFAULT NULL,
  fecha_creacion datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado tinyint(1) NOT NULL DEFAULT 1,
  id_tipo_documento int DEFAULT NULL,
  numero_documento varchar(20) DEFAULT NULL,
  id_ubigeo int DEFAULT NULL,
CREATE TABLE oportunidades (
  id_oportunidad int NOT NULL AUTO_INCREMENT,
  id_cliente int NOT NULL,
  nombre_oportunidad varchar(100) NOT NULL,
  valor_estimado decimal(10, 2) DEFAULT NULL,
  fecha_cierre date DEFAULT NULL,
  etapa enum ('Calificación', 'Propuesta', 'Negociación', 'Ganada', 'Perdida') NOT NULL DEFAULT 'Calificación',
  fecha_creacion datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id_oportunidad)
)
CREATE TABLE contactos (
  id_contacto int NOT NULL AUTO_INCREMENT,
  id_cliente int NOT NULL,
  nombre_contacto varchar(100) NOT NULL,
  cargo_contacto varchar(100) DEFAULT NULL,
  correo_contacto varchar(100) DEFAULT NULL,
  telefono_contacto varchar(20) DEFAULT NULL,
  fecha_creacion datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id_contacto)
)
CREATE TABLE perfiles (
  id_perfil int NOT NULL AUTO_INCREMENT,
  nombre_perfil varchar(50) NOT NULL,
  estado tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id_perfil)
)
ENGINE = INNODB,
AUTO_INCREMENT = 4,
AVG_ROW_LENGTH = 5461,
CHARACTER SET utf8mb4,
COLLATE utf8mb4_general_ci,
CREATE TABLE usuarios (
  id_usuario int NOT NULL AUTO_INCREMENT,
  nombre_usuario varchar(50) NOT NULL,
  apellido_usuario varchar(50) NOT NULL,
  correo_usuario varchar(100) NOT NULL,
  clave_usuario varchar(255) NOT NULL,
  id_perfil int NOT NULL,
  estado tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id_usuario)
)
ENGINE = INNODB,
CREATE TABLE actividades (
  id_actividad int NOT NULL AUTO_INCREMENT,
  id_cliente int DEFAULT NULL,
  id_contacto int DEFAULT NULL,
  id_oportunidad int DEFAULT NULL,
  id_usuario int NOT NULL,
  tipo_actividad enum ('Llamada', 'Reunión', 'Correo', 'Tarea') NOT NULL,
  asunto varchar(255) NOT NULL,
  descripcion text DEFAULT NULL,
  fecha_actividad datetime NOT NULL,
  fecha_creacion datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
