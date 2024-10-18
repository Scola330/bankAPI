-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Paź 18, 2024 at 08:45 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `banco_de_skibidi`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `hash_data`
--

CREATE TABLE `hash_data` (
  `ID` int(11) NOT NULL,
  `account_hash` varchar(255) NOT NULL COMMENT 'sha-256',
  `user_number` int(100) NOT NULL,
  `ip` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hash_data`
--

INSERT INTO `hash_data` (`ID`, `account_hash`, `user_number`, `ip`) VALUES
(1, 'd8f209530415e20a8023c1ef57a6f3ac633d89c09f24f62b015c70f620b0ad98', 1, '0');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `hash_data`
--
ALTER TABLE `hash_data`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_number` (`user_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hash_data`
--
ALTER TABLE `hash_data`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hash_data`
--
ALTER TABLE `hash_data`
  ADD CONSTRAINT `hash_data_ibfk_1` FOREIGN KEY (`user_number`) REFERENCES `user` (`ID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
