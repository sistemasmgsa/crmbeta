-- Base de datos: `crmbeta`
CREATE DATABASE IF NOT EXISTS `crmbeta` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `crmbeta`;

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_perfil` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`id_perfil`, `nombre_perfil`, `estado`) VALUES
(1, 'Administrador', 1),
(2, 'Usuario', 1);

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(50) NOT NULL,
  `apellido_usuario` varchar(50) NOT NULL,
  `correo_usuario` varchar(100) NOT NULL,
  `clave_usuario` varchar(255) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_usuario`),
  KEY `id_perfil` (`id_perfil`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`nombre_usuario`, `apellido_usuario`, `correo_usuario`, `clave_usuario`, `id_perfil`) VALUES
('Admin', 'User', 'admin@crm.com', '$2y$10$rs8VKcPTRGHdb9H6Uly6DOEB45Qm0E5hEo29z3cpyoa/m4WYBKoTe', 1);
