-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 19 Sty 2021, 09:43
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
(1, 'Test1', '06848510221296801582662798', 58500);

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
  `nr_konta` varchar(255) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=16384 DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `klienci`
--

INSERT INTO `klienci` (`id`, `imie_nazwisko`, `adres`, `kod_pocztowy`, `miejscowosc`, `nr_konta`) VALUES
(21, 'Imię nazwisko', 'ul. Rzeszowska', '35-601', 'Rzeszów', '40000000000000000000000000'),
(22, 'Imię nazwisko 2', 'ul. Rzeszowska', '35-601', 'Rzeszów', '40000000000000000000000000'),
(25, 'Imię nazwisko', 'ul. Rzeszowska', '35-601', 'Rzeszów', '40000000000000000000000000'),
(26, 'Imię nazwisko 2', 'ul. Rzeszowska', '35-601', 'Rzeszów', '40000000000000000000000000');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przelewy`
--

CREATE TABLE `przelewy` (
  `id` int(11) NOT NULL,
  `kwota` float NOT NULL,
  `id_nadawcy` int(11) NOT NULL,
  `id_odbiorcy` int(11) NOT NULL,
  `typ` varchar(255) DEFAULT NULL,
  `czas` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `zweryfikowany` tinyint(1) NOT NULL,
  `status` enum('UNVERIFIED','AWAITS_MANUAL_VERIFICATION','VERIFIED') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `przelewy`
--
ALTER TABLE `przelewy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_przelewy_id_nadawcy` (`id_nadawcy`),
  ADD KEY `FK_przelewy_id_odbiorcy` (`id_odbiorcy`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `banki`
--
ALTER TABLE `banki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `klienci`
--
ALTER TABLE `klienci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT dla tabeli `przelewy`
--
ALTER TABLE `przelewy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `przelewy`
--
ALTER TABLE `przelewy`
  ADD CONSTRAINT `FK_przelewy_id_nadawcy` FOREIGN KEY (`id_nadawcy`) REFERENCES `klienci` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_przelewy_id_odbiorcy` FOREIGN KEY (`id_odbiorcy`) REFERENCES `klienci` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
