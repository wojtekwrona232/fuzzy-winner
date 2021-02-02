-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 02 Lut 2021, 20:54
-- Wersja serwera: 10.4.17-MariaDB
-- Wersja PHP: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `jr`
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
(1, 'Test1', '06848510220000000000000000', 24500),
(2, 'Test 2', '08848510250000000000000000', 32000);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klienci`
--

CREATE TABLE `klienci` (
  `id` int(11) NOT NULL,
  `imie_nazwisko` varchar(255) NOT NULL,
  `adres` varchar(255) NOT NULL,
  `kod_pocztowy` varchar(255) NOT NULL,
  `miejscowosc` varchar(255) NOT NULL,
  `nr_konta` varchar(255) NOT NULL,
  `id_bank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `klienci`
--

INSERT INTO `klienci` (`id`, `imie_nazwisko`, `adres`, `kod_pocztowy`, `miejscowosc`, `nr_konta`, `id_bank`) VALUES
(2, 'Imię nazwisko', 'ul. Rzeszowska', '35-601', 'Rzeszów', '40000000000000000000000000', 1),
(8, 'Imię nazwisko 2', 'ul. Rzeszowska', '35-601', 'Rzeszów', '40000000000000000000000000', 2),
(9, 'Imię nazwisko', 'ul. Rzeszowska', '35-601', 'Rzeszów', '40000000000000000000000000', 2),
(10, 'Imię nazwisko 2', 'ul. Rzeszowska', '35-601', 'Rzeszów', '40000000000000000000000000', 1),
(11, 'Imię nazwisko', 'ul. Rzeszowska', '35-601', 'Rzeszów', '40000000000000000000000020', 1),
(12, 'Imię nazwisko', 'ul. Rzeszowska', '35-601', 'Rzeszów', '40000000000000000000000020', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przelewy`
--

CREATE TABLE `przelewy` (
  `id` int(11) NOT NULL,
  `kwota` float NOT NULL,
  `id_nadawcy` int(11) NOT NULL,
  `id_odbiorcy` int(11) NOT NULL,
  `czas` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `zweryfikowany` tinyint(1) NOT NULL,
  `status` enum('UNVERIFIED','AWAITS_MANUAL_VERIFICATION','VERIFIED','REJECTED','EXECUTED_TRUE','EXECUTED_FALSE') NOT NULL,
  `tytul` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `przelewy`
--

INSERT INTO `przelewy` (`id`, `kwota`, `id_nadawcy`, `id_odbiorcy`, `czas`, `zweryfikowany`, `status`, `tytul`) VALUES
(32, 500, 9, 10, '2020-01-12 17:50:00', 1, 'VERIFIED', 'Przelew'),
(33, 500, 9, 10, '2020-01-12 17:50:00', 1, 'VERIFIED', 'Przelew'),
(34, 500, 9, 10, '2021-01-30 01:44:18', 1, 'REJECTED', 'Przelew'),
(35, 500, 9, 10, '2021-01-30 01:44:18', 1, 'REJECTED', 'Przelew'),
(36, 2000, 9, 11, '2021-01-30 01:29:37', 1, 'REJECTED', 'Przelew'),
(37, 500, 9, 10, '2020-01-12 17:50:00', 1, 'VERIFIED', 'Przelew'),
(38, 2000, 9, 11, '2021-02-02 20:50:00', 1, 'EXECUTED_TRUE', 'Przelew'),
(39, 500, 2, 8, '2020-01-12 17:50:00', 1, 'VERIFIED', 'Przelew'),
(40, 2000, 2, 12, '2020-01-12 17:50:00', 0, 'AWAITS_MANUAL_VERIFICATION', 'Przelew'),
(41, 500, 2, 8, '2020-01-12 17:50:00', 1, 'VERIFIED', 'Przelew'),
(42, 2000, 2, 12, '2020-01-12 17:50:00', 0, 'AWAITS_MANUAL_VERIFICATION', 'Przelew'),
(43, 500, 2, 8, '2020-01-12 17:50:00', 1, 'VERIFIED', 'Przelew'),
(44, 2000, 2, 12, '2020-01-12 17:50:00', 0, 'AWAITS_MANUAL_VERIFICATION', 'Przelew'),
(45, 500, 9, 10, '2020-01-12 17:50:00', 1, 'VERIFIED', 'Przelew'),
(46, 2000, 9, 11, '2021-02-02 20:50:00', 1, 'EXECUTED_FALSE', 'Przelew'),
(47, 500, 9, 10, '2020-01-12 17:50:00', 1, 'VERIFIED', 'Przelew'),
(48, 2000, 9, 11, '2020-01-12 17:50:00', 0, 'AWAITS_MANUAL_VERIFICATION', 'Przelew'),
(49, 500, 9, 10, '2020-01-12 17:50:00', 1, 'VERIFIED', 'Przelew'),
(50, 2000, 9, 11, '2020-01-12 17:50:00', 0, 'AWAITS_MANUAL_VERIFICATION', 'Przelew');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `banki`
--
ALTER TABLE `banki`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `klienci`
--
ALTER TABLE `klienci`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_klienci_id_bank` (`id_bank`);

--
-- Indeksy dla tabeli `przelewy`
--
ALTER TABLE `przelewy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_przelewy_id_nadawcy2` (`id_nadawcy`),
  ADD KEY `FK_przelewy_id_odbiorcy2` (`id_odbiorcy`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `banki`
--
ALTER TABLE `banki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
