-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-02-2016 a las 23:53:23
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

--
-- Volcado de datos para la tabla `comunidad`
--

INSERT INTO `comunidad` (`id`, `tid`, `name`) VALUES
('UK782729', 179580, 'La Premier Toñi');

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

--
-- Volcado de datos para la tabla `event`
--

INSERT INTO `event` (`matchday`, `rating_who`, `rating`, `goals`, `yellow_cards`, `red_cards`, `player_id`) VALUES
(69, 6.47, 1, 0, 0, 0, 78498),
(70, 6.69, 2, 0, 0, 0, 97692),
(69, 7.84, 9, 1, 0, 0, 25964),
(70, 7.43, 4, 0, 0, 0, 52197),
(69, 0, 0, 0, 0, 0, 91540),
(69, 0, 0, 0, 0, 0, 4065),
(70, 7.4, 4, 0, 0, 0, 8157),
(69, 8.35, 8, 0, 0, 0, 95408),
(69, 6.93, 3, 0, 0, 0, 85059),
(69, 6.89, 3, 0, 0, 0, 86173),
(69, 0, 0, 0, 0, 0, 8140),
(69, 0, 0, 0, 0, 0, 83078),
(69, 7.58, 9, 1, 0, 0, 12480),
(69, 7.24, 4, 0, 0, 0, 12376),
(69, 8.25, 11, 1, 0, 0, 14058),
(69, 7.08, 3, 0, 0, 0, 92547),
(69, 8.6, 11, 1, 0, 0, 24248),
(70, 8.78, 13, 1, 0, 0, 111212),
(69, 0, 0, 0, 0, 0, 31402),
(69, 0, 0, 0, 0, 0, 21683),
(69, 6.78, 2, 0, 0, 0, 31376),
(70, 8.73, 9, 0, 0, 0, 24444),
(69, 4.95, -5, 0, 0, 0, 13450),
(69, 0, 0, 0, 0, 0, 109915),
(69, 6.55, 2, 0, 0, 0, 15338),
(70, 6.92, 6, 1, 0, 0, 83532),
(69, 6.01, 0, 0, 0, 0, 73399),
(69, 6.74, 2, 0, 0, 0, 70),
(69, 6.92, 3, 0, 0, 0, 6105),
(69, 7.1, 3, 0, 0, 0, 24827),
(69, 6.71, 2, 0, 0, 0, 70033),
(69, 5.98, 0, 0, 0, 0, 23220),
(69, 9.26, 14, 1, 0, 0, 29463),
(69, 0, 0, 0, 0, 0, 81726),
(69, 7.12, 3, 0, 1, 0, 188),
(69, 7.1, 3, 0, 0, 0, 4574),
(69, 6.76, 2, 0, 0, 0, 33404),
(69, 5.28, -4, 0, 0, 0, 69517),
(70, 8.91, 9, 0, 0, 0, 80767),
(69, 7.99, 10, 1, 0, 0, 8247),
(70, 7.98, 6, 0, 0, 0, 12187),
(70, 7.06, 3, 0, 0, 0, 25244),
(69, 0, 0, 0, 0, 0, 12874),
(69, 6.28, 1, 0, 1, 0, 3859),
(70, 7.29, 7, 1, 0, 0, 13796),
(70, 5.86, -1, 0, 0, 0, 8786),
(70, 6.87, 3, 0, 1, 0, 30051),
(70, 6.99, 3, 0, 0, 0, 6775),
(69, 0, 0, 0, 0, 0, 101374),
(69, 6.74, 2, 0, 1, 0, 9298),
(69, 0, 0, 0, 0, 0, 38772),
(70, 5.85, -1, 0, 0, 0, 14168),
(69, 7.74, 5, 0, 0, 0, 24148),
(69, 7.07, 3, 0, 0, 0, 78221),
(69, 6.38, 1, 0, 1, 0, 90780),
(69, 0, 0, 0, 0, 0, 21778),
(70, 7.42, 8, 1, 0, 0, 69344),
(69, 6.88, 7, 1, 0, 0, 68648),
(69, 7.24, 4, 0, 0, 0, 33590),
(69, 7.01, 3, 0, 0, 0, 69867),
(70, 5.94, -1, 0, 0, 0, 68312),
(69, 7.74, 5, 0, 1, 0, 105172),
(69, 5.85, -1, 0, 0, 0, 14308),
(69, 6.58, 2, 0, 0, 0, 10737),
(69, 8.94, 15, 2, 0, 0, 25832),
(69, 0, 0, 0, 0, 0, 34693),
(69, 7.6, 5, 0, 0, 0, 3860),
(69, 6.14, 0, 0, 0, 0, 107395),
(70, 6.79, 2, 0, 1, 0, 12431),
(69, 7.28, 4, 0, 0, 0, 8408),
(70, 7.28, 4, 0, 0, 0, 69778),
(70, 8.64, 8, 0, 0, 0, 68659),
(70, 6.5, 1, 0, 0, 0, 14053),
(69, 7.19, 4, 0, 0, 0, 33886),
(69, 6.44, 1, 0, 0, 0, 69346),
(70, 6.69, 2, 0, 0, 0, 131519),
(70, 6.47, 1, 0, 0, 0, 26222),
(69, 6.29, 1, 0, 0, 0, 83895),
(69, 0, 0, 0, 0, 0, 28746),
(70, 6.89, 3, 0, 1, 1, 23683),
(70, 6.43, 1, 0, 0, 0, 12267),
(69, 6.26, 1, 0, 0, 0, 71345),
(70, 7.13, 3, 0, 0, 0, 69933),
(70, 7.08, 3, 0, 1, 0, 26820),
(70, 7.18, 4, 0, 0, 0, 75138),
(70, 6.48, 1, 0, 0, 0, 104749),
(72, 0, 0, 0, 0, 0, 4065),
(72, 7.13, 3, 0, 0, 0, 95408),
(72, 6.75, 2, 0, 0, 0, 86173),
(72, 0, 0, 0, 0, 0, 22079),
(72, 5.99, 0, 0, 0, 0, 38128),
(72, 6.43, 1, 0, 1, 0, 12376),
(72, 0, 0, 0, 0, 0, 92547),
(73, 6.03, 0, 0, 0, 0, 24444),
(72, 6.19, 0, 0, 0, 0, 109915),
(73, 7.4, 4, 0, 0, 0, 83532),
(72, 7.47, 5, 0, 0, 0, 73399),
(72, 7.58, 5, 0, 0, 0, 24827),
(72, 0, 0, 0, 0, 0, 23220),
(72, 0, 0, 0, 0, 0, 81726),
(72, 0, 0, 0, 0, 0, 4574),
(72, 6.85, 3, 0, 0, 0, 33404),
(72, 0, 0, 0, 0, 0, 80767),
(72, 0, 0, 0, 0, 0, 12187),
(72, 0, 0, 0, 0, 0, 12874),
(73, 5.9, -1, 0, 0, 0, 13796),
(73, 5.73, -2, 0, 0, 0, 6775),
(72, 7.56, 5, 0, 0, 0, 69877),
(72, 7.91, 6, 0, 0, 0, 9298),
(72, 6.48, 1, 0, 0, 0, 78221),
(72, 0, 0, 0, 0, 0, 21778),
(72, 6.32, 1, 0, 0, 0, 68648),
(73, 6.82, 2, 0, 0, 0, 69738),
(72, 0, 0, 0, 0, 0, 69867),
(72, 5.88, -1, 0, 0, 0, 105172),
(72, 7.9, 12, 2, 0, 0, 34693),
(72, 6.52, 1, 0, 0, 0, 3860),
(72, 6.05, 0, 0, 0, 0, 107395),
(72, 7.66, 5, 0, 0, 0, 12431),
(72, 7.21, 4, 0, 0, 0, 4145),
(73, 7.3, 4, 0, 1, 0, 69778),
(72, 7.49, 5, 0, 0, 0, 122945),
(72, 0, 0, 0, 0, 0, 29474),
(72, 7.35, 4, 0, 0, 0, 8505),
(72, 6.78, 2, 0, 0, 0, 33886),
(73, 7.59, 5, 0, 0, 0, 131519),
(72, 7.93, 6, 0, 0, 0, 66741),
(72, 0, 0, 0, 0, 0, 83895),
(72, 0, 0, 0, 0, 0, 28746),
(72, 0, 0, 0, 0, 0, 113275),
(72, 0, 0, 0, 0, 0, 12267),
(72, 0, 0, 0, 0, 0, 71345),
(73, 6.97, 3, 0, 0, 0, 69933),
(73, 6.26, 1, 0, 1, 0, 26820),
(72, 7.05, 3, 0, 0, 0, 75138),
(72, 6.67, 2, 0, 0, 0, 104749),
(72, 0, 0, 0, 0, 0, 78498),
(72, 0, 0, 0, 0, 0, 97692),
(72, 6.86, 3, 0, 0, 0, 25964),
(72, 0, 0, 0, 0, 0, 52197),
(72, 0, 0, 0, 0, 0, 106086),
(72, 0, 0, 0, 0, 0, 91540),
(72, 7.37, 4, 0, 0, 0, 8157),
(72, 6.6, 2, 0, 0, 0, 85059),
(72, 0, 0, 0, 0, 0, 8140),
(72, 7.18, 4, 0, 0, 0, 12480),
(72, 7.27, 4, 0, 0, 0, 14058),
(72, 7.58, 5, 0, 1, 0, 24248),
(72, 5.94, -1, 0, 0, 0, 31402),
(72, 6.14, 0, 0, 0, 0, 31376),
(72, 0, 0, 0, 0, 0, 13450),
(72, 8.3, 12, 1, 0, 0, 15338),
(72, 0, 0, 0, 0, 0, 6105),
(72, 0, 0, 0, 0, 0, 69375),
(73, 6.81, 2, 0, 0, 0, 70033),
(72, 8.48, 8, 0, 0, 0, 29463),
(72, 0, 0, 0, 0, 0, 188),
(72, 7.94, 10, 1, 0, 0, 69517),
(72, 6.78, 2, 0, 0, 0, 8247),
(73, 6.6, 2, 0, 0, 0, 25244),
(72, 0, 0, 0, 0, 0, 3859),
(72, 0, 0, 0, 0, 0, 8786),
(73, 6.69, 2, 0, 0, 0, 30051),
(72, 0, 0, 0, 0, 0, 101374),
(72, 6.48, 1, 0, 0, 0, 38772),
(72, 6.63, 2, 0, 0, 0, 24148),
(72, 6.6, 2, 0, 0, 0, 90780),
(73, 8.2, 7, 0, 0, 0, 69344),
(72, 6.28, 1, 0, 0, 0, 33590),
(72, 0, 0, 0, 0, 0, 68312),
(73, 5.94, -1, 0, 0, 0, 14308),
(72, 6.58, 2, 0, 0, 0, 25832),
(72, 5.95, 0, 0, 0, 0, 70676);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `matchday`
--

CREATE TABLE `matchday` (
  `id` int(11) NOT NULL,
  `matchday` tinyint(4) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Premier League schedule by matchday';

--
-- Volcado de datos para la tabla `matchday`
--

INSERT INTO `matchday` (`id`, `matchday`, `date`) VALUES
(2, 1, '2015-08-08'),
(3, 1, '2015-08-09'),
(4, 1, '2015-08-10'),
(5, 2, '2015-08-15'),
(6, 2, '2015-08-16'),
(7, 2, '2015-08-17'),
(8, 3, '2015-08-22'),
(9, 3, '2015-08-23'),
(10, 3, '2015-08-24'),
(11, 4, '2015-08-29'),
(12, 4, '2015-08-30'),
(13, 4, '2015-08-31'),
(14, 5, '2015-09-12'),
(15, 5, '2015-09-13'),
(16, 5, '2015-09-14'),
(17, 6, '2015-09-19'),
(18, 6, '2015-09-20'),
(19, 6, '2015-09-21'),
(20, 7, '2015-09-26'),
(21, 7, '2015-09-27'),
(22, 7, '2015-09-28'),
(23, 8, '2015-10-03'),
(24, 8, '2015-10-04'),
(25, 8, '2015-10-05'),
(26, 9, '2015-10-17'),
(27, 9, '2015-10-18'),
(28, 9, '2015-10-19'),
(29, 10, '2015-10-24'),
(30, 10, '2015-10-25'),
(31, 10, '2015-10-26'),
(32, 11, '2015-10-31'),
(33, 11, '2015-11-01'),
(34, 11, '2015-11-02'),
(35, 12, '2015-11-07'),
(36, 12, '2015-11-08'),
(37, 12, '2015-11-09'),
(38, 13, '2015-11-21'),
(39, 13, '2015-11-22'),
(40, 13, '2015-11-23'),
(41, 14, '2015-11-28'),
(42, 14, '2015-11-29'),
(43, 14, '2015-11-30'),
(44, 15, '2015-12-05'),
(45, 15, '2015-12-06'),
(46, 15, '2015-12-07'),
(47, 16, '2015-12-12'),
(48, 16, '2015-12-13'),
(49, 16, '2015-12-14'),
(50, 17, '2015-12-19'),
(51, 17, '2015-12-20'),
(52, 17, '2015-12-21'),
(53, 18, '2015-12-26'),
(54, 19, '2015-12-28'),
(55, 19, '2015-12-29'),
(56, 19, '2015-12-30'),
(57, 20, '2016-01-02'),
(58, 20, '2016-01-03'),
(59, 21, '2016-01-12'),
(60, 21, '2016-01-13'),
(61, 22, '2016-01-16'),
(62, 22, '2016-01-17'),
(63, 22, '2016-01-18'),
(64, 23, '2016-01-23'),
(65, 23, '2016-01-24'),
(66, 24, '2016-02-02'),
(67, 24, '2016-02-03'),
(68, 25, '2016-02-06'),
(69, 26, '2016-02-13'),
(70, 26, '2016-02-14'),
(71, 26, '2016-02-15'),
(72, 27, '2016-02-27'),
(73, 27, '2016-02-28'),
(74, 27, '2016-02-29'),
(75, 28, '2016-03-01'),
(76, 28, '2016-03-02'),
(77, 28, '2016-03-03'),
(78, 29, '2016-03-05'),
(79, 29, '2016-03-06'),
(80, 29, '2016-03-07'),
(81, 30, '2016-03-12'),
(82, 30, '2016-03-13'),
(83, 30, '2016-03-14'),
(84, 31, '2016-03-19'),
(85, 31, '2016-03-20'),
(86, 31, '2016-03-21'),
(87, 32, '2016-04-02'),
(88, 32, '2016-04-03'),
(89, 32, '2016-04-04'),
(90, 33, '2016-04-09'),
(91, 33, '2016-04-10'),
(92, 33, '2016-04-11'),
(93, 34, '2016-04-16'),
(94, 34, '2016-04-17'),
(95, 34, '2016-04-18'),
(96, 35, '2016-04-23'),
(97, 35, '2016-04-24'),
(98, 35, '2016-04-25'),
(99, 36, '2016-04-30'),
(100, 36, '2016-05-01'),
(101, 36, '2016-05-02'),
(102, 37, '2016-05-07'),
(103, 37, '2016-05-08'),
(104, 37, '2016-05-09'),
(105, 38, '2016-05-15'),
(106, 38, '2016-05-16'),
(107, 38, '2016-05-17'),
(108, 25, '2016-02-07');

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

--
-- Volcado de datos para la tabla `player`
--

INSERT INTO `player` (`id`, `name`, `who_name`, `value`, `pos`, `team`, `user_id`, `user_old_id`, `url`) VALUES
(70, 'Terry', 'John Terry', 990000, 'Defender', 4, 958498, 0, 'http://www.whoscored.com/Players/70/'),
(188, 'Barry', 'Gareth Barry', 3420000, 'Midfielder', 6, 958498, 0, 'http://www.whoscored.com/Players/188/'),
(2859, 'Coloccini', 'Fabricio Coloccini', 440000, 'Defender', 11, 782729, 0, 'http://www.whoscored.com/Players/2859/'),
(3859, 'Rooney', 'Wayne Rooney', 12990000, 'Striker', 10, 958498, 0, 'http://www.whoscored.com/Players/3859/'),
(3860, 'Walters', 'Jonathan Walters', 1020000, 'Striker', 14, 784922, 0, 'http://www.whoscored.com/Players/3860/'),
(4065, 'Stekelenburg', 'Maarten Stekelenburg', 170000, 'Goalkeeper', 13, 782729, 0, 'http://www.whoscored.com/Players/4065/'),
(4145, 'Huth', 'Robert Huth', 8040000, 'Defender', 7, 785522, 0, 'http://www.whoscored.com/Players/4145/'),
(4574, 'Johnson', 'Glen Johnson', 1780000, 'Defender', 14, 784818, 0, 'http://www.whoscored.com/Players/4574/'),
(6105, 'Jagielka', 'Phil Jagielka', 1130000, 'Defender', 6, 958498, 0, 'http://www.whoscored.com/Players/6105/'),
(6775, 'Cech', 'Petr Cech', 7710000, 'Goalkeeper', 2, 784922, 0, 'http://www.whoscored.com/Players/6775/'),
(8140, 'Davis', 'Kelvin Davis', 2050000, 'Midfielder', 13, 786647, 0, 'http://www.whoscored.com/Players/8140/'),
(8157, 'Morgan', 'Wes Morgan', 3460000, 'Defender', 7, 786647, 0, 'http://www.whoscored.com/Players/8157/'),
(8247, 'Noble', 'Mark Noble', 3720000, 'Midfielder', 20, 958498, 0, 'http://www.whoscored.com/Players/8247/'),
(8408, 'Williams', 'Ashley Williams', 4870000, 'Defender', 16, 785522, 0, 'http://www.whoscored.com/Players/8408/'),
(8505, 'Whelan', 'Glenn Whelan', 1040000, 'Midfielder', 14, 785522, 0, 'http://www.whoscored.com/Players/8505/'),
(8643, 'Ruddy', 'John Ruddy', 170000, 'Goalkeeper', 12, 958498, 0, 'http://www.whoscored.com/Players/8643/'),
(8786, 'Hart', 'Joe Hart', 3230000, 'Goalkeeper', 9, 1106190, 0, 'http://www.whoscored.com/Players/8786/'),
(9298, 'Francis', 'Simon Francis', 2360000, 'Defender', 1, 784922, 0, 'http://www.whoscored.com/Players/9298/'),
(10737, 'Jerome', 'Cameron Jerome', 330000, 'Striker', 12, 784922, 0, 'http://www.whoscored.com/Players/10737/'),
(12187, 'Dembélé', 'Mousa Dembélé', 9830000, 'Midfielder', 17, 784818, 0, 'http://www.whoscored.com/Players/12187/'),
(12267, 'Kolarov', 'Aleksandar Kolarov', 5830000, 'Defender', 9, 785613, 0, 'http://www.whoscored.com/Players/12267/'),
(12376, 'Cabaye', 'Yohan Cabaye', 10220000, 'Midfielder', 5, 782729, 0, 'http://www.whoscored.com/Players/12376/'),
(12431, 'Fuchs', 'Christian Fuchs', 7090000, 'Defender', 7, 785522, 0, 'http://www.whoscored.com/Players/12431/'),
(12480, 'Afellay', 'Ibrahim Afellay', 1130000, 'Midfielder', 14, 786647, 0, 'http://www.whoscored.com/Players/12480/'),
(12874, 'Motta', 'Marco Motta', 160000, 'Midfielder', 18, 784818, 0, 'http://www.whoscored.com/Players/12874/'),
(13450, 'Elliot', 'Robert Elliot', 170000, 'Goalkeeper', 11, 958498, 0, 'http://www.whoscored.com/Players/13450/'),
(13796, 'Walcott', 'Theo Walcott', 4650000, 'Striker', 2, 784818, 0, 'http://www.whoscored.com/Players/13796/'),
(14053, 'Touré', 'Yaya Touré', 15220000, 'Midfielder', 9, 785522, 0, 'http://www.whoscored.com/Players/14053/'),
(14058, 'Payet', 'Dimitri Payet', 16090000, 'Midfielder', 20, 786647, 0, 'http://www.whoscored.com/Players/14058/'),
(14102, 'Silva', 'David Silva', 9130000, 'Midfielder', 9, 785522, 0, 'http://www.whoscored.com/Players/14102/'),
(14168, 'Richards', 'Micah Richards', 2070000, 'Defender', 3, 784922, 0, 'http://www.whoscored.com/Players/14168/'),
(14244, 'Zabaleta', 'Pablo Zabaleta', 310000, 'Defender', 9, 785613, 0, 'http://www.whoscored.com/Players/14244/'),
(14308, 'Gomis', 'Bafétimbi Gomis', 1020000, 'Striker', 16, 1106190, 0, 'http://www.whoscored.com/Players/14308/'),
(15338, 'Ivanovic', 'Branislav Ivanovic', 5890000, 'Defender', 4, 958498, 0, 'http://www.whoscored.com/Players/15338/'),
(18600, 'Abdi', 'Almen Abdi', 1090000, 'Midfielder', 18, 784922, 0, 'http://www.whoscored.com/Players/18600/'),
(19177, 'Suarez', 'Mario Suárez', 720000, 'Midfielder', 18, 958498, 0, 'http://www.whoscored.com/Players/19177/'),
(19277, 'Reid', 'Winston Reid', 660000, 'Defender', 20, 784922, 0, 'http://www.whoscored.com/Players/19277/'),
(19471, 'Sagna', 'Bacary Sagna', 2100000, 'Defender', 9, 785522, 0, 'http://www.whoscored.com/Players/19471/'),
(21683, 'Lallana', 'Adam Lallana', 4030000, 'Midfielder', 8, 782729, 0, 'http://www.whoscored.com/Players/21683/'),
(21778, 'Vertonghen', 'Jan Vertonghen', 3990000, 'Defender', 17, 784922, 0, 'http://www.whoscored.com/Players/21778/'),
(22079, 'Evans', 'Jonny Evans', 4590000, 'Defender', 19, 782729, 0, 'http://www.whoscored.com/Players/22079/'),
(22319, 'Krul', 'Tim Krul', 180000, 'Goalkeeper', 11, 958498, 0, 'http://www.whoscored.com/Players/22319/'),
(23220, 'Darmian', 'Matteo Darmian', 2030000, 'Defender', 10, 784818, 0, 'http://www.whoscored.com/Players/23220/'),
(23383, 'Carroll', 'Andy Carroll', 990000, 'Striker', 20, 785522, 0, 'http://www.whoscored.com/Players/23383/'),
(23547, 'Ogbonna', 'Angelo Ogbonna', 1040000, 'Defender', 20, 784922, 0, 'http://www.whoscored.com/Players/23547/'),
(23683, 'Simpson', 'Danny Simpson', 3640000, 'Defender', 7, 785613, 0, 'http://www.whoscored.com/Players/23683/'),
(24148, 'Pieters', 'Erik Pieters', 3690000, 'Defender', 14, 1106190, 0, 'http://www.whoscored.com/Players/24148/'),
(24248, 'Costa', 'Diego Costa', 16070000, 'Striker', 4, 786647, 0, 'http://www.whoscored.com/Players/24248/'),
(24444, 'Giroud', 'Olivier Giroud', 14120000, 'Striker', 2, 782729, 0, 'http://www.whoscored.com/Players/24444/'),
(24827, 'Daniels', 'Charlie Daniels', 4190000, 'Defender', 1, 784818, 0, 'http://www.whoscored.com/Players/24827/'),
(25244, 'Sanchez', 'Alexis Sánchez', 19110000, 'Striker', 2, 958498, 0, 'http://www.whoscored.com/Players/25244/'),
(25832, 'Deeney', 'Troy Deeney', 11610000, 'Striker', 18, 1106190, 0, 'http://www.whoscored.com/Players/25832/'),
(25964, 'Rondon', 'Salomón Rondón', 1990000, 'Striker', 19, 785613, 0, 'http://www.whoscored.com/Players/25964/'),
(26222, 'Okazaki', 'Shinji Okazaki', 3370000, 'Striker', 7, 785522, 0, 'http://www.whoscored.com/Players/26222/'),
(26820, 'Ramsey', 'Aaron Ramsey', 11180000, 'Midfielder', 2, 785613, 0, 'http://www.whoscored.com/Players/26820/'),
(28746, 'Guzan', 'Brad Guzan', 180000, 'Goalkeeper', 3, 785613, 0, 'http://www.whoscored.com/Players/28746/'),
(29463, 'Willian', 'Willian', 12200000, 'Midfielder', 4, 958498, 0, 'http://www.whoscored.com/Players/29463/'),
(29474, 'Tadic', 'Dusan Tadic', 4480000, 'Midfielder', 13, 785522, 0, 'http://www.whoscored.com/Players/29474/'),
(30051, 'Koscielny', 'Laurent Koscielny', 9700000, 'Defender', 2, 1106190, 0, 'http://www.whoscored.com/Players/30051/'),
(31376, 'Ighalo', 'Odion Ighalo', 17470000, 'Striker', 18, 786647, 0, 'http://www.whoscored.com/Players/31376/'),
(31402, 'Bojan', 'Bojan', 4080000, 'Striker', 14, 786647, 0, 'http://www.whoscored.com/Players/31402/'),
(33403, 'Gestede', 'Rudy Gestede', 1510000, 'Striker', 3, 785613, 0, 'http://www.whoscored.com/Players/33403/'),
(33404, 'Hazard', 'Eden Hazard', 12850000, 'Midfielder', 4, 784818, 0, 'http://www.whoscored.com/Players/33404/'),
(33590, 'Capoue', 'Etienne Capoue', 3690000, 'Midfielder', 18, 1106190, 0, 'http://www.whoscored.com/Players/33590/'),
(33886, 'M''Vila', 'Yann M''Vila', 4770000, 'Midfielder', 15, 785522, 0, 'http://www.whoscored.com/Players/33886/'),
(34693, 'Arnautovic', 'Marko Arnautovic', 10560000, 'Striker', 14, 784922, 0, 'http://www.whoscored.com/Players/34693/'),
(38128, 'Matic', 'Nemanja Matic', 3030000, 'Midfielder', 4, 782729, 0, 'http://www.whoscored.com/Players/38128/'),
(38772, 'Cameron', 'Geoff Cameron', 410000, 'Defender', 14, 1106190, 0, 'http://www.whoscored.com/Players/38772/'),
(41065, 'Oscar', 'Oscar', 3870000, 'Midfielder', 4, 785613, 0, 'http://www.whoscored.com/Players/41065/'),
(44847, 'Fernandez', 'Federico Fernández', 610000, 'Defender', 16, 785522, 0, 'http://www.whoscored.com/Players/44847/'),
(52197, 'Mignolet', 'Simon Mignolet', 880000, 'Goalkeeper', 8, 786647, 0, 'http://www.whoscored.com/Players/52197/'),
(66741, 'Kouyate', 'Cheikhou Kouyaté', 6170000, 'Midfielder', 20, 785522, 0, 'http://www.whoscored.com/Players/66741/'),
(68312, 'Benteke', 'Christian Benteke', 3690000, 'Striker', 8, 1106190, 0, 'http://www.whoscored.com/Players/68312/'),
(68648, 'Ritchie', 'Matt Ritchie', 2770000, 'Midfielder', 1, 784922, 0, 'http://www.whoscored.com/Players/68648/'),
(68659, 'Henderson', 'Jordan Henderson', 3410000, 'Midfielder', 8, 785522, 0, 'http://www.whoscored.com/Players/68659/'),
(69344, 'Eriksen', 'Christian Eriksen', 11890000, 'Midfielder', 17, 1106190, 0, 'http://www.whoscored.com/Players/69344/'),
(69346, 'Sigurdsson', 'Gylfi Sigurdsson', 4470000, 'Midfielder', 16, 785522, 0, 'http://www.whoscored.com/Players/69346/'),
(69375, 'Clyne', 'Nathaniel Clyne', 2260000, 'Defender', 8, 958498, 0, 'http://www.whoscored.com/Players/69375/'),
(69517, 'Antonio', 'Michail Antonio', 2200000, 'Midfielder', 20, 958498, 0, 'http://www.whoscored.com/Players/69517/'),
(69738, 'Coquelin', 'Francis Coquelin', 2270000, 'Midfielder', 2, 784922, 0, 'http://www.whoscored.com/Players/69738/'),
(69778, 'Walker', 'Kyle Walker', 3920000, 'Defender', 17, 785522, 0, 'http://www.whoscored.com/Players/69778/'),
(69867, 'Colback', 'Jack Colback', 300000, 'Midfielder', 11, 784922, 0, 'http://www.whoscored.com/Players/69867/'),
(69877, 'Smith', 'Adam Smith', 1810000, 'Defender', 1, 784922, 0, 'http://www.whoscored.com/Players/69877/'),
(69878, 'Mason', 'Ryan Mason', 180000, 'Midfielder', 17, 784922, 0, 'http://www.whoscored.com/Players/69878/'),
(69933, 'Alderweireld', 'Toby Alderweireld', 6570000, 'Defender', 17, 785613, 0, 'http://www.whoscored.com/Players/69933/'),
(70033, 'Blind', 'Daley Blind', 5840000, 'Defender', 10, 958498, 0, 'http://www.whoscored.com/Players/70033/'),
(70676, 'Obiang', 'Pedro Obiang', 390000, 'Midfielder', 20, 0, 782729, 'http://www.whoscored.com/Players/70676/'),
(71345, 'Smalling', 'Chris Smalling', 5080000, 'Defender', 10, 785613, 0, 'http://www.whoscored.com/Players/71345/'),
(71714, 'Wanyama', 'Victor Wanyama', 1120000, 'Midfielder', 13, 958498, 0, 'http://www.whoscored.com/Players/71714/'),
(73399, 'Adrian', 'Adrián', 1220000, 'Goalkeeper', 20, 784818, 0, 'http://www.whoscored.com/Players/73399/'),
(75138, 'Drinkwater', 'Daniel Drinkwater', 7800000, 'Midfielder', 7, 785613, 0, 'http://www.whoscored.com/Players/75138/'),
(78221, 'Aanholt', 'Patrick van Aanholt', 9050000, 'Defender', 15, 784922, 0, 'http://www.whoscored.com/Players/78221/'),
(78498, 'Lukaku', 'Romelu Lukaku', 16550000, 'Striker', 6, 785613, 0, 'http://www.whoscored.com/Players/78498/'),
(80767, 'Coutinho', 'Philippe Coutinho', 11980000, 'Midfielder', 8, 784818, 0, 'http://www.whoscored.com/Players/80767/'),
(81726, 'Jones', 'Phil Jones', 180000, 'Defender', 10, 784818, 0, 'http://www.whoscored.com/Players/81726/'),
(83078, 'Trippier', 'Kieran Trippier', 490000, 'Defender', 17, 0, 782729, 'http://www.whoscored.com/Players/83078/'),
(83532, 'Kane', 'Harry Kane', 20620000, 'Striker', 17, 782729, 0, 'http://www.whoscored.com/Players/83532/'),
(83895, 'Valencia', 'Enner Valencia', 2910000, 'Striker', 20, 785522, 0, 'http://www.whoscored.com/Players/83895/'),
(85059, 'Zaha', 'Wilfried Zaha', 4310000, 'Midfielder', 5, 786647, 0, 'http://www.whoscored.com/Players/85059/'),
(86173, 'Wollscheid', 'Philipp Wollscheid', 2060000, 'Defender', 14, 782729, 0, 'http://www.whoscored.com/Players/86173/'),
(90780, 'Romeu', 'Oriol Romeu', 1180000, 'Midfielder', 13, 1106190, 0, 'http://www.whoscored.com/Players/90780/'),
(91540, 'Mori', 'Rogelio Funes Mori', 4890000, 'Defender', 6, 786647, 0, 'http://www.whoscored.com/Players/91540/'),
(92547, 'Barkley', 'Ross Barkley', 17430000, 'Midfielder', 6, 782729, 0, 'http://www.whoscored.com/Players/92547/'),
(95408, 'Dijk', 'Virgil van Dijk', 10770000, 'Defender', 13, 782729, 0, 'http://www.whoscored.com/Players/95408/'),
(97692, 'Sterling', 'Raheem Sterling', 8110000, 'Striker', 9, 785613, 0, 'http://www.whoscored.com/Players/97692/'),
(101374, 'Stones', 'John Stones', 2790000, 'Defender', 6, 1106190, 0, 'http://www.whoscored.com/Players/101374/'),
(104749, 'Mahrez', 'Riyad Mahrez', 18950000, 'Midfielder', 7, 785613, 0, 'http://www.whoscored.com/Players/104749/'),
(105172, 'Ward-Prowse', 'James Ward-Prowse', 840000, 'Midfielder', 13, 784922, 0, 'http://www.whoscored.com/Players/105172/'),
(106086, 'Zouma', 'Kurt Zouma', 1790000, 'Defender', 4, 786647, 0, 'http://www.whoscored.com/Players/106086/'),
(107395, 'Butland', 'Jack Butland', 3700000, 'Goalkeeper', 14, 785522, 0, 'http://www.whoscored.com/Players/107395/'),
(109915, 'Mane', 'Sadio Mané', 5610000, 'Striker', 13, 782729, 0, 'http://www.whoscored.com/Players/109915/'),
(111212, 'Can', 'Emre Can', 4440000, 'Midfielder', 8, 782729, 0, 'http://www.whoscored.com/Players/111212/'),
(113275, 'Moreno', 'Alberto Moreno', 3710000, 'Defender', 8, 785613, 0, 'http://www.whoscored.com/Players/113275/'),
(122945, 'Ake', 'Nathan Aké', 1460000, 'Defender', 18, 785522, 0, 'http://www.whoscored.com/Players/122945/'),
(126958, 'Yedlin', 'DeAndre Yedlin', 250000, 'Defender', 15, 784818, 0, 'http://www.whoscored.com/Players/126958/'),
(131519, 'Alli', 'Dele Alli', 17410000, 'Midfielder', 17, 785522, 0, 'http://www.whoscored.com/Players/131519/');

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

--
-- Volcado de datos para la tabla `team`
--

INSERT INTO `team` (`id`, `name`, `name_who`, `premier`) VALUES
(0, 'N/A', 'N/A', 0),
(1, 'AFC Bournemouth', 'Bournemouth', 1),
(2, 'Arsenal FC', 'Arsenal', 1),
(3, 'Aston Villa', 'Aston Villa', 1),
(4, 'Chelsea FC', 'Chelsea', 1),
(5, 'Crystal Palace', 'Crystal Palace', 1),
(6, 'Everton FC', 'Everton', 1),
(7, 'Leicester City', 'Leicester', 1),
(8, 'Liverpool FC', 'Liverpool', 1),
(9, 'Manchester City', 'Manchester City', 1),
(10, 'Manchester United', 'Manchester United', 1),
(11, 'Newcastle United', 'Newcastle United', 1),
(12, 'Norwich City', 'Norwich', 1),
(13, 'Southampton FC', 'Southampton', 1),
(14, 'Stoke City', 'Stoke', 1),
(15, 'Sunderland AFC', 'Sunderland', 1),
(16, 'Swansea City', 'Swansea', 1),
(17, 'Tottenham Hotspur', 'Tottenham', 1),
(18, 'Watford FC', 'Watford', 1),
(19, 'West Bromwich Albion', 'West Bromwich Albion', 1),
(20, 'West Ham United', 'West Ham', 1);

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
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`pid`, `id`, `name`, `comunidad`) VALUES
(0, '0', '0', 'UK782729'),
(782729, 'Danie254', 'Daniel García', 'UK782729'),
(784818, 'rafi23', 'rafi', 'UK782729'),
(784922, 'sehira10', 'Hidalgo', 'UK782729'),
(785522, 'montes', 'JAVIER', 'UK782729'),
(785613, 'TheOnlyOne', 'Felipe Giraldo', 'UK782729'),
(786647, 'RubenRodri', 'Rubén Rodríguez', 'UK782729'),
(958498, 'Ish', 'Iñaki Sola', 'UK782729'),
(1106190, 'Aldam4ship', 'aldama otero', 'UK782729');

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
