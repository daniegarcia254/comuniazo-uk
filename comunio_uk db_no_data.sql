-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-02-2016 a las 23:53:42
-- Versión del servidor: 10.1.9-MariaDB
-- Versión de PHP: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comunio_uk`
--
CREATE DATABASE IF NOT EXISTS `comunio_uk` DEFAULT CHARACTER SET utf32 COLLATE utf32_general_ci;
USE `comunio_uk`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunidad`
--

CREATE TABLE `comunidad` (
  `id` varchar(20) NOT NULL,
  `tid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Comunidades de la página www.comunio.co.uk';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `event`
--

CREATE TABLE `event` (
  `matchday` int(11) DEFAULT NULL,
  `rating_who` double DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `goals` int(11) NOT NULL,
  `yellow_cards` tinyint(4) NOT NULL,
  `red_cards` tinyint(4) NOT NULL,
  `player_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matchday`
--

CREATE TABLE `matchday` (
  `id` int(11) NOT NULL,
  `matchday` tinyint(4) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Premier League schedule by matchday';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `player`
--

CREATE TABLE `player` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `who_name` varchar(50) NOT NULL,
  `value` int(11) NOT NULL,
  `pos` enum('Goalkeeper','Midfielder','Defender','Striker') NOT NULL,
  `team` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_old_id` int(11) NOT NULL DEFAULT '0',
  `url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Premier League players';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `team`
--

CREATE TABLE `team` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `name_who` varchar(50) NOT NULL,
  `premier` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Premier League teams';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `pid` int(11) NOT NULL,
  `id` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `comunidad` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Comunio users';

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comunidad`
--
ALTER TABLE `comunidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `event`
--
ALTER TABLE `event`
  ADD UNIQUE KEY `matchday` (`matchday`,`player_id`),
  ADD KEY `event_ibfk_2` (`player_id`);

--
-- Indices de la tabla `matchday`
--
ALTER TABLE `matchday`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `team` (`team`),
  ADD KEY `user_old_id` (`user_old_id`);

--
-- Indices de la tabla `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `comunidad` (`comunidad`),
  ADD KEY `pid` (`pid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `matchday`
--
ALTER TABLE `matchday`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`matchday`) REFERENCES `matchday` (`id`),
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `player_ibfk_1` FOREIGN KEY (`team`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `player_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`pid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `player_ibfk_3` FOREIGN KEY (`user_old_id`) REFERENCES `user` (`pid`);

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`comunidad`) REFERENCES `comunidad` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
