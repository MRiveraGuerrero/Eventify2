-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 19-11-2023 a las 16:52:19
-- Versión del servidor: 10.8.2-MariaDB-1:10.8.2+maria~focal
-- Versión de PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos`
--
DROP TABLE IF EXISTS `accesos`;
CREATE TABLE `accesos` (
  `usuario` varchar(50) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `intentos` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `accesos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--
DROP TABLE IF EXISTS `eventos`;
CREATE TABLE `eventos` (
  `usuario` varchar(50) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `enunciado` varchar(255) NOT NULL,
  `opcion1` varchar(255) NOT NULL,
  `resultado1` varchar(255) NOT NULL,
  `opcion2` varchar(255) NOT NULL,
  `resultado2` varchar(255) NOT NULL,
  `likes` int(10) NOT NULL DEFAULT 0,
  UNIQUE(`usuario`, `titulo`),
  `fecha` TIMESTAMP NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`usuario`, `titulo`, `enunciado`, `opcion1`, `resultado1`, `opcion2`, `resultado2`, `likes`) VALUES
('ImanolMM', '¡¡Enanos!!', 'Te despiertas de una larga siesta y estas rodeado de enanos, Quita! Son demasiados y te estan intentando agarrar para meterte en una caja! Despues de un tiempo siendo transportado ves que te han llevado a su aldea.', 'Te intentas liberar y peleas contra ellos', 'Te dañan pero consigues escapar', 'Usas tu linterna para intentar sorprenderles', 'Están sorprendidos. Nunca antes habían visto algo así,  te toman por su dios y te dan de comer y beber', 0);
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `nombre` text NOT NULL,
  `telef` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nacimiento` date NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `sal` varchar(50) NOT NULL,
  UNIQUE(`usuario`),
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`nombre`, `telef`, `email`, `nacimiento`, `usuario`, `passwd`, `sal`) VALUES
('invitado', 1, 'invitado', '2003-08-08', 'invitado', 'invitado', 'invitado'),
('ImanolMM', 684399392, 'imanolm.upv@gmail.com', '2003-08-08', 'ImanolMM', 'imanolMM', 'a');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD PRIMARY KEY (`usuario`,`ip`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`usuario`,`titulo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario`);

--
-- Restricciones para tablas volcadas
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE `likes` (
  `usuarioCreador` varchar(50) NOT NULL,
  `tituloEv` varchar(255) NOT NULL,
  `usuarioLike` varchar(50) NOT NULL,
  PRIMARY KEY (`usuarioCreador`, `tituloEv`, `usuarioLike`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `comentarios`;
CREATE TABLE `comentarios` (
  `usuarioCreador` varchar(50) NOT NULL,
  `tituloEv` varchar(255) NOT NULL,
  `usuarioComent` varchar(50) NOT NULL,
  `fechaHora` datetime NOT NULL,
  `comentario` varchar(1000) NOT NULL,
  PRIMARY KEY (`usuarioCreador`, `tituloEv`, `usuarioComent`, `fechaHora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `follows`;
CREATE TABLE `follows` (
  `usuarioSeguidor` varchar(50) NOT NULL,
  `usuarioSeguido` varchar(50) NOT NULL,
  PRIMARY KEY (`usuarioSeguidor`, `usuarioSeguido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `block`;
CREATE TABLE `block` (
  `usuarioBloqueador` varchar(50) NOT NULL,
  `usuarioBloqueado` varchar(50) NOT NULL,
  PRIMARY KEY (`usuarioBloqueador`, `usuarioBloqueado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



DROP TABLE IF EXISTS `follows`;
CREATE TABLE `follows` (
  `usuarioSeguidor` varchar(50) NOT NULL,
  `usuarioSeguido` varchar(50) NOT NULL,
  PRIMARY KEY (`usuarioSeguidor`, `usuarioSeguido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `mensajes`;
CREATE TABLE `mensajes` (
  `usuarioA` varchar(50) NOT NULL,
  `usuarioB` varchar(50) NOT NULL,
  `mensaje` varchar(255) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`usuarioA`, `usuarioB`, `fecha`);
COMMIT;

--
-- Filtros para la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD CONSTRAINT `accesos_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`);

--
-- Filtros para la tabla `likes`
--

ALTER TABLE `likes`
ADD CONSTRAINT FOREIGN KEY (`usuarioCreador`, `tituloEv`) REFERENCES `eventos`(`usuario`, `titulo`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_usuarioLike` FOREIGN KEY (`usuarioLike`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `comentarios`
--

ALTER TABLE `comentarios`
ADD CONSTRAINT FOREIGN KEY (`usuarioCreador`, `tituloEv`) REFERENCES `eventos`(`usuario`, `titulo`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_usuarioComent` FOREIGN KEY (`usuarioComent`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `follows`
--

ALTER TABLE `follows`
ADD CONSTRAINT `fk_usuarioSeguidor` FOREIGN KEY (`usuarioSeguidor`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_usuarioSeguido` FOREIGN KEY (`usuarioSeguido`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `block`
--

ALTER TABLE `block`
ADD CONSTRAINT `fk_usuarioBloqueador` FOREIGN KEY (`usuarioBloqueador`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_usuarioBloqueado` FOREIGN KEY (`usuarioBloqueado`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE;

ALTER TABLE `mensajes`
ADD CONSTRAINT FOREIGN KEY (`usuarioA`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE;

ALTER TABLE `eventos`
ADD CONSTRAINT FOREIGN KEY (`usuario`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `likes`
--

ALTER TABLE `likes`
ADD CONSTRAINT `fk_usuarioCreador` FOREIGN KEY (`usuarioCreador`) REFERENCES `eventos`(`usuario`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_tituloEv` FOREIGN KEY (`tituloEv`) REFERENCES `eventos`(`titulo`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_usuarioLike` FOREIGN KEY (`usuarioLike`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `comentarios`
--

ALTER TABLE `comentarios`
ADD CONSTRAINT `fk_usuarioCreador` FOREIGN KEY (`usuarioCreador`) REFERENCES `eventos`(`usuario`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_tituloEv` FOREIGN KEY (`tituloEv`) REFERENCES `eventos`(`titulo`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_usuarioComent` FOREIGN KEY (`usuarioComent`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `follows`
--

ALTER TABLE `follows`
ADD CONSTRAINT `fk_usuarioSeguidor` FOREIGN KEY (`usuarioSeguidor`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_usuarioSeguido` FOREIGN KEY (`usuarioSeguido`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `block`
--

ALTER TABLE `block`
ADD CONSTRAINT `fk_usuarioBloqueador` FOREIGN KEY (`usuarioBloqueador`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE,
ADD CONSTRAINT `fk_usuarioBloqueado` FOREIGN KEY (`usuarioBloqueado`) REFERENCES `usuarios`(`usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;