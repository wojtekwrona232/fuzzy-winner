-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Czas generowania: 06 Lut 2021, 19:54
-- Wersja serwera: 5.7.33-0ubuntu0.18.04.1
-- Wersja PHP: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `freedbtech_pabbjr`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `banki`
--

CREATE TABLE `banki` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(255) NOT NULL,
  `nr_konta` varchar(255) NOT NULL,
  `balans` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `banki`
--

INSERT INTO `banki` (`id`, `nazwa`, `nr_konta`, `balans`) VALUES
(3, 'Diamond Holdings', '73132421540000000000000000', 7923),
(4, 'Bank 2', '15102052260000000000000000', 7203);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klienci`
--

CREATE TABLE `klienci` (
  `id` int(11) NOT NULL,
  `imie_nazwisko` varchar(255) NOT NULL,
  `adres` varchar(255) DEFAULT NULL,
  `kod_pocztowy` varchar(255) DEFAULT NULL,
  `miejscowosc` varchar(255) DEFAULT NULL,
  `nr_konta` varchar(255) NOT NULL,
  `id_bank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przelewy`
--

CREATE TABLE `przelewy` (
  `id` int(11) NOT NULL,
  `kwota` float NOT NULL,
  `id_nadawcy` int(11) NOT NULL,
  `id_odbiorcy` int(11) NOT NULL,
  `czas` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `zweryfikowany` tinyint(1) NOT NULL,
  `status` enum('UNVERIFIED','AWAITS_MANUAL_VERIFICATION','VERIFIED','REJECTED','EXECUTED_TRUE','EXECUTED_FALSE') NOT NULL,
  `tytul` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `banki`
--
ALTER TABLE `banki`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `klienci`
--
ALTER TABLE `klienci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_klienci_id_bank` (`id_bank`);

--
-- Indexes for table `przelewy`
--
ALTER TABLE `przelewy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_przelewy_id_nadawcy2` (`id_nadawcy`),
  ADD KEY `FK_przelewy_id_odbiorcy2` (`id_odbiorcy`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `banki`
--
ALTER TABLE `banki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `klienci`
--
ALTER TABLE `klienci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT dla tabeli `przelewy`
--
ALTER TABLE `przelewy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `klienci`
--
ALTER TABLE `klienci`
  ADD CONSTRAINT `FK_klienci_id_bank` FOREIGN KEY (`id_bank`) REFERENCES `banki` (`id`) ON DELETE NO ACTION;

--
-- Ograniczenia dla tabeli `przelewy`
--
ALTER TABLE `przelewy`
  ADD CONSTRAINT `FK_przelewy_id_nadawcy2` FOREIGN KEY (`id_nadawcy`) REFERENCES `klienci` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `FK_przelewy_id_odbiorcy2` FOREIGN KEY (`id_odbiorcy`) REFERENCES `klienci` (`id`) ON DELETE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
