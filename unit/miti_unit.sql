-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 13, 2014 at 09:21 AM
-- Server version: 5.5.37
-- PHP Version: 5.3.10-1ubuntu3.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `miti_unit`
--

-- --------------------------------------------------------

--
-- Table structure for table `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `id` tinyint(3) unsigned NOT NULL,
  `nome` varchar(30) NOT NULL,
  `status` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categoria`
--

INSERT INTO `categoria` (`id`, `nome`, `status`) VALUES
(1, 'Filme', 'a'),
(2, 'Música', 'b'),
(3, 'Pintura', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `memoria`
--

CREATE TABLE IF NOT EXISTS `memoria` (
  `id` tinyint(4) NOT NULL,
  `descricao` varchar(1000) NOT NULL,
  `categoria` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria` (`categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `memoria`
--

INSERT INTO `memoria` (`id`, `descricao`, `categoria`) VALUES
(1, 'Peaceful Warrior', 1),
(2, 'The Village', 1),
(3, 'Let Her Go', 2);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id` char(1) NOT NULL,
  `descricao` varchar(20) NOT NULL,
  `prioridade` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `descricao`, `prioridade`) VALUES
('a', 'Ativo', 1),
('b', 'Inativo', 1),
('c', 'Não aplicável', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `categoria_ibfk_1` FOREIGN KEY (`status`) REFERENCES `status` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `memoria`
--
ALTER TABLE `memoria`
  ADD CONSTRAINT `memoria_ibfk_1` FOREIGN KEY (`categoria`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
