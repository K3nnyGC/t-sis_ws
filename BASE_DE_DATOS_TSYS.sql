-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 15-06-2018 a las 19:50:10
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `tsys3`
--
CREATE DATABASE `t_sys` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `t_sys`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `advisors`
--

CREATE TABLE IF NOT EXISTS `advisors` (
  `dni_advisor` varchar(8) NOT NULL,
  `email` varchar(256) NOT NULL,
  `pasword` varchar(32) NOT NULL,
  `name` varchar(256) NOT NULL,
  `lastname` varchar(256) NOT NULL,
  `address` varchar(256) NOT NULL,
  `phone` varchar(9) NOT NULL,
  `card` varchar(32) DEFAULT NULL,
  `credit_card` varchar(32) DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `status_advisor` int(11) DEFAULT NULL,
  `prom_score` double DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL,
  `picture` text,
  PRIMARY KEY (`dni_advisor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `advisors_details`
--

CREATE TABLE IF NOT EXISTS `advisors_details` (
  `code_detail` int(11) NOT NULL AUTO_INCREMENT,
  `dni_advisor` varchar(8) NOT NULL,
  `code_university` int(11) NOT NULL,
  `code_grade` int(11) NOT NULL,
  `year_egress` int(11) NOT NULL,
  PRIMARY KEY (`code_detail`),
  KEY `ADVISORS_DETAILS_ADVISORS_FK` (`dni_advisor`),
  KEY `ADVISORS_DETAILS_GRADES_FK` (`code_grade`),
  KEY `ADVISORS_DETAILS_UNIVERSITIES_FK` (`code_university`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `availables`
--

CREATE TABLE IF NOT EXISTS `availables` (
  `code_available_time` int(11) NOT NULL,
  `dni_advisor` varchar(8) NOT NULL,
  `date` date NOT NULL,
  `hour` int(11) NOT NULL,
  `status_available` int(11) NOT NULL,
  PRIMARY KEY (`code_available_time`),
  KEY `AVAILABLES_ADVISORS_FK` (`dni_advisor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `careers`
--

CREATE TABLE IF NOT EXISTS `careers` (
  `code_career` int(11) NOT NULL,
  `description_career` varchar(256) NOT NULL,
  `status_career` int(11) NOT NULL,
  PRIMARY KEY (`code_career`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contracts`
--

CREATE TABLE IF NOT EXISTS `contracts` (
  `code_contract` int(11) NOT NULL AUTO_INCREMENT,
  `code_available_time` int(11) NOT NULL,
  `code_knowledge` int(11) NOT NULL,
  `dni_student` varchar(8) NOT NULL,
  `code_grade` int(11) NOT NULL,
  `date_registry` date NOT NULL,
  `date_advisory` date NOT NULL,
  `state_contract` int(11) NOT NULL,
  `method_payment` int(11) NOT NULL,
  `score_contract` int(11) DEFAULT NULL,
  PRIMARY KEY (`code_contract`),
  KEY `CONTRACTS_AVAILABLES_FK` (`code_available_time`),
  KEY `CONTRACTS_KNOWLEDGE_FK` (`code_knowledge`),
  KEY `CONTRACTS_STUDENTS_FK` (`dni_student`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grades`
--

CREATE TABLE IF NOT EXISTS `grades` (
  `code_grade` int(11) NOT NULL,
  `description_grade` varchar(256) NOT NULL,
  PRIMARY KEY (`code_grade`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `knowledge`
--

CREATE TABLE IF NOT EXISTS `knowledge` (
  `code_knowledge` int(11) NOT NULL AUTO_INCREMENT,
  `dni_advisor` varchar(8) NOT NULL,
  `code_career` int(11) NOT NULL,
  `name_theme` varchar(256) NOT NULL,
  `desciption_theme` varchar(256) NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`code_knowledge`),
  KEY `KNOWLEDGE_ADVISORS_FK` (`dni_advisor`),
  KEY `KNOWLEDGE_CAREERS_FK` (`code_career`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `dni_student` varchar(8) NOT NULL,
  `name` varchar(256) NOT NULL,
  `lastname` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(32) NOT NULL,
  `address` varchar(256) DEFAULT NULL,
  `phone` varchar(9) DEFAULT NULL,
  `card` varchar(32) DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`dni_student`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `universities`
--

CREATE TABLE IF NOT EXISTS `universities` (
  `code_university` int(11) NOT NULL,
  `description_university` varchar(256) NOT NULL,
  `status_university` int(11) DEFAULT NULL,
  PRIMARY KEY (`code_university`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `advisors_details`
--
ALTER TABLE `advisors_details`
  ADD CONSTRAINT `ADVISORS_DETAILS_ADVISORS_FK` FOREIGN KEY (`dni_advisor`) REFERENCES `advisors` (`dni_advisor`),
  ADD CONSTRAINT `ADVISORS_DETAILS_GRADES_FK` FOREIGN KEY (`code_grade`) REFERENCES `grades` (`code_grade`),
  ADD CONSTRAINT `ADVISORS_DETAILS_UNIVERSITIES_FK` FOREIGN KEY (`code_university`) REFERENCES `universities` (`code_university`);

--
-- Filtros para la tabla `availables`
--
ALTER TABLE `availables`
  ADD CONSTRAINT `AVAILABLES_ADVISORS_FK` FOREIGN KEY (`dni_advisor`) REFERENCES `advisors` (`dni_advisor`);

--
-- Filtros para la tabla `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `CONTRACTS_AVAILABLES_FK` FOREIGN KEY (`code_available_time`) REFERENCES `availables` (`code_available_time`),
  ADD CONSTRAINT `CONTRACTS_KNOWLEDGE_FK` FOREIGN KEY (`code_knowledge`) REFERENCES `knowledge` (`code_knowledge`),
  ADD CONSTRAINT `CONTRACTS_STUDENTS_FK` FOREIGN KEY (`dni_student`) REFERENCES `students` (`dni_student`);

--
-- Filtros para la tabla `knowledge`
--
ALTER TABLE `knowledge`
  ADD CONSTRAINT `KNOWLEDGE_ADVISORS_FK` FOREIGN KEY (`dni_advisor`) REFERENCES `advisors` (`dni_advisor`),
  ADD CONSTRAINT `KNOWLEDGE_CAREERS_FK` FOREIGN KEY (`code_career`) REFERENCES `careers` (`code_career`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;