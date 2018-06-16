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
-- Estructura de tabla para la tabla `tb_available_knowledge`
--

CREATE TABLE IF NOT EXISTS `tb_available_knowledge` (
  `co_avai_know` int(11) NOT NULL AUTO_INCREMENT,
  `tb_user_teacher_co_dni_teacher` varchar(8) NOT NULL,
  `tb_career_co_career` int(11) NOT NULL,
  `no_theme` varchar(256) NOT NULL,
  `des_theme` varchar(500) NOT NULL,
  `av_kn_price` double NOT NULL,
  PRIMARY KEY (`co_avai_know`),
  KEY `tb_available_knowledge_tb_career_FK` (`tb_career_co_career`),
  KEY `tb_available_knowledge_tb_user_teacher_FK` (`tb_user_teacher_co_dni_teacher`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_career`
--

CREATE TABLE IF NOT EXISTS `tb_career` (
  `co_career` int(11) NOT NULL,
  `des_career` varchar(256) NOT NULL,
  `status_career` int(11) DEFAULT NULL,
  PRIMARY KEY (`co_career`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_contract`
--

CREATE TABLE IF NOT EXISTS `tb_contract` (
  `co_contract` int(11) NOT NULL AUTO_INCREMENT,
  `tb_hours_available_co_hours_ava` int(11) NOT NULL,
  `tb_available_knowledge_co_avai_know` int(11) NOT NULL,
  `co_grade` int(11) DEFAULT NULL,
  `tb_user_student_co_dni_student` varchar(8) DEFAULT NULL,
  `date_registry` date DEFAULT NULL,
  `date_advisory` date DEFAULT NULL,
  `estate_contract` int(11) DEFAULT NULL,
  `method_payment` int(11) DEFAULT NULL,
  `score_contract` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`co_contract`),
  KEY `tb_contract_tb_available_knowledge_FK` (`tb_available_knowledge_co_avai_know`),
  KEY `tb_contract_tb_hours_available_FK` (`tb_hours_available_co_hours_ava`),
  KEY `tb_contract_tb_user_student_FK` (`tb_user_student_co_dni_student`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_grade`
--

CREATE TABLE IF NOT EXISTS `tb_grade` (
  `co_grade` int(11) NOT NULL,
  `des_grade` varchar(256) NOT NULL,
  PRIMARY KEY (`co_grade`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_hours_available`
--

CREATE TABLE IF NOT EXISTS `tb_hours_available` (
  `co_hours_ava` int(11) NOT NULL AUTO_INCREMENT,
  `tb_user_teacher_co_dni_teacher` varchar(8) NOT NULL,
  `co_day` int(11) DEFAULT NULL,
  `co_hour` int(11) DEFAULT NULL,
  `status_hours_ava` int(11) DEFAULT NULL,
  PRIMARY KEY (`co_hours_ava`),
  KEY `tb_hours_available_tb_user_teacher_FK` (`tb_user_teacher_co_dni_teacher`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_teacher_detail_uni`
--

CREATE TABLE IF NOT EXISTS `tb_teacher_detail_uni` (
  `co_detail_uni` int(11) NOT NULL AUTO_INCREMENT,
  `tb_user_teacher_co_dni_teacher` varchar(8) NOT NULL,
  `tb_university_co_university` int(11) NOT NULL,
  `tb_grade_co_grade` int(11) DEFAULT NULL,
  `year_egress` int(11) DEFAULT NULL,
  PRIMARY KEY (`co_detail_uni`),
  KEY `tb_teacher_detail_uni_tb_grade_FK` (`tb_grade_co_grade`),
  KEY `tb_teacher_detail_uni_tb_university_FK` (`tb_university_co_university`),
  KEY `tb_teacher_detail_uni_tb_user_teacher_FK` (`tb_user_teacher_co_dni_teacher`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_university`
--

CREATE TABLE IF NOT EXISTS `tb_university` (
  `co_university` int(11) NOT NULL,
  `des_university` varchar(256) NOT NULL,
  `status_university` int(11) DEFAULT NULL,
  PRIMARY KEY (`co_university`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_user_student`
--

CREATE TABLE IF NOT EXISTS `tb_user_student` (
  `co_dni_student` varchar(8) NOT NULL,
  `co_user_email` varchar(256) NOT NULL,
  `co_user_name` varchar(256) NOT NULL,
  `co_user_lastname` varchar(256) NOT NULL,
  `co_user_address` varchar(256) DEFAULT NULL,
  `co_user_password` varchar(32) NOT NULL,
  `co_user_phone` varchar(16) DEFAULT NULL,
  `co_user_card` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`co_dni_student`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_user_teacher`
--

CREATE TABLE IF NOT EXISTS `tb_user_teacher` (
  `co_dni_teacher` varchar(8) NOT NULL,
  `des_user_email` varchar(256) NOT NULL,
  `des_user_password` varchar(32) NOT NULL,
  `des_user_name` varchar(256) NOT NULL,
  `des_user_lasttname` varchar(256) NOT NULL,
  `des_user_address` varchar(256) DEFAULT NULL,
  `des_user_phone` varchar(16) DEFAULT NULL,
  `co_user_card` int(11) DEFAULT NULL,
  `num_userd_credit_card` varchar(32) DEFAULT NULL,
  `num_user_nu_latitude` double DEFAULT NULL,
  `num_user_nu_longitude` double DEFAULT NULL,
  `status_user` int(11) DEFAULT NULL,
  `prom_score` double DEFAULT NULL,
  PRIMARY KEY (`co_dni_teacher`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tb_user_teacher`
--

INSERT INTO `tb_user_teacher` (`co_dni_teacher`, `des_user_email`, `des_user_password`, `des_user_name`, `des_user_lasttname`, `des_user_address`, `des_user_phone`, `co_user_card`, `num_userd_credit_card`, `num_user_nu_latitude`, `num_user_nu_longitude`, `status_user`, `prom_score`) VALUES
('12345678', 'correo@mail.com', '202cb962ac59075b964b07152d234b70', 'Kenny', 'Gonzales', 'Av Calle numero', '987654321', 1, '1', 123456467543, 8.7635424, 2, 4.342);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tb_available_knowledge`
--
ALTER TABLE `tb_available_knowledge`
  ADD CONSTRAINT `tb_available_knowledge_tb_career_FK` FOREIGN KEY (`tb_career_co_career`) REFERENCES `tb_career` (`co_career`),
  ADD CONSTRAINT `tb_available_knowledge_tb_user_teacher_FK` FOREIGN KEY (`tb_user_teacher_co_dni_teacher`) REFERENCES `tb_user_teacher` (`co_dni_teacher`);

--
-- Filtros para la tabla `tb_contract`
--
ALTER TABLE `tb_contract`
  ADD CONSTRAINT `tb_contract_tb_available_knowledge_FK` FOREIGN KEY (`tb_available_knowledge_co_avai_know`) REFERENCES `tb_available_knowledge` (`co_avai_know`),
  ADD CONSTRAINT `tb_contract_tb_hours_available_FK` FOREIGN KEY (`tb_hours_available_co_hours_ava`) REFERENCES `tb_hours_available` (`co_hours_ava`),
  ADD CONSTRAINT `tb_contract_tb_user_student_FK` FOREIGN KEY (`tb_user_student_co_dni_student`) REFERENCES `tb_user_student` (`co_dni_student`);

--
-- Filtros para la tabla `tb_hours_available`
--
ALTER TABLE `tb_hours_available`
  ADD CONSTRAINT `tb_hours_available_tb_user_teacher_FK` FOREIGN KEY (`tb_user_teacher_co_dni_teacher`) REFERENCES `tb_user_teacher` (`co_dni_teacher`);

--
-- Filtros para la tabla `tb_teacher_detail_uni`
--
ALTER TABLE `tb_teacher_detail_uni`
  ADD CONSTRAINT `tb_teacher_detail_uni_tb_grade_FK` FOREIGN KEY (`tb_grade_co_grade`) REFERENCES `tb_grade` (`co_grade`),
  ADD CONSTRAINT `tb_teacher_detail_uni_tb_university_FK` FOREIGN KEY (`tb_university_co_university`) REFERENCES `tb_university` (`co_university`),
  ADD CONSTRAINT `tb_teacher_detail_uni_tb_user_teacher_FK` FOREIGN KEY (`tb_user_teacher_co_dni_teacher`) REFERENCES `tb_user_teacher` (`co_dni_teacher`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
