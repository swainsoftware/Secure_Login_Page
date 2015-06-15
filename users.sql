-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2015 at 02:11 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(4) NOT NULL,
  `login` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `hash` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `salt` varchar(75) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `firstname` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `secondname` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `loggedin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `hash`, `salt`, `firstname`, `secondname`, `email`, `loggedin`) VALUES
(16, 'demo', '$2y$12$v76jCRmozhE9bR0yknkm7eR4CGNjaE2POt3/9KYNX8rzKA3p51MuS', 'v76jCRmozhE9bR0yknkm7kIa7PQy4w==', 'Demo User', 'DemoUser Secondname', 'andrei.rjabokon@somewhere.net', 0),
(28, 'user', '$2y$12$FX3dLkZG8FneUhgGfClmYe/4j6dh0J8oUTlxSgViKfJC6HPXPJuAa', 'FX3dLkZG8FneUhgGfClmYsVTBpbCRg==', 'Andrei', 'Rjabokon', 'andrei.rjabokon@somewhere.net', 0),
(35, 'andrei', '$2y$12$RLrmV5rMzOj9bDklO0oaEeFZQxEffVfVReaeZEwpXO9vfZvg0QEPS', 'RLrmV5rMzOj9bDklO0oaEh8kLUG6pA==', 'Andrei', 'Rjabokon', 'andrei.rjabokon@somewhere.net', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
