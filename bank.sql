-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 08 Lut 2021, 18:02
-- Wersja serwera: 10.4.17-MariaDB
-- Wersja PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `bank`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numer` varchar(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `saldo` decimal(20,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `accounts`
--

INSERT INTO `accounts` (`id`, `numer`, `saldo`, `created_at`, `updated_at`) VALUES
(1, '46132421540000000000000001', '0.00', '2021-02-02 12:53:09', NULL),
(2, '19132421540000000000000002', '0.00', '2021-02-02 12:53:09', NULL),
(3, '89132421540000000000000003', '0.00', '2021-02-02 12:53:09', NULL),
(4, '39132421540000000000001000', '2000000.00', '2021-02-02 12:53:09', NULL),
(5, '23132421540000000000253876', '250.00', '2021-02-02 12:53:09', NULL),
(6, '86132421545870516397407055', '25000.00', '2021-02-02 12:53:09', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `haslo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imie_nazwisko` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `PESEL` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adres` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kod_pocztowy` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `miejscowosc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jestPracownikiem` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `clients`
--

INSERT INTO `clients` (`id`, `login`, `haslo`, `imie_nazwisko`, `PESEL`, `adres`, `kod_pocztowy`, `miejscowosc`, `jestPracownikiem`, `created_at`, `updated_at`) VALUES
(1, '123456', '$2y$10$jjQqLCRjFHBO10AdYFqdKOhXggSBG6JI5RyINtvT/CpucitHW23g.', 'Adam Adamski', '77122451696', 'ul. Krakowska 20', '35123', 'Rzeszow', 0, '2021-02-02 12:53:08', NULL),
(2, '43432145', '$2y$10$GCshGTdVgljV8ZUKOZjlKOO3eViIdGOnqTL/yw4RLR0eXHs/zaVbO', 'Bartłomiej Babacki', '79121695936', 'ul. Rzeszowska 15', '30012', 'Kraków', 0, '2021-02-02 12:53:09', NULL),
(3, '111111', '$2y$10$5PgSJdfSQ/.rJ6Tx6ZriOOCS/j8lhv3iJfh028as1L00kBr/pvex.', 'Czesław Cabacki', '56021773438', 'ul. Krótka 75', '00001', 'Warszawa', 1, '2021-02-02 12:53:09', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `client_accounts`
--

CREATE TABLE `client_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_klienta` bigint(20) UNSIGNED NOT NULL,
  `id_konta` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `client_accounts`
--

INSERT INTO `client_accounts` (`id`, `id_klienta`, `id_konta`, `created_at`, `updated_at`) VALUES
(1, 1, 5, '2021-02-02 12:53:12', NULL),
(2, 2, 6, '2021-02-02 12:53:12', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(37, '2021_01_10_125148_create_clients_table', 1),
(38, '2021_01_10_130007_create_accounts_table', 1),
(39, '2021_01_10_130925_create_transfers_table', 1),
(40, '2021_01_10_132703_create_client_accounts_table', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `transfers`
--

CREATE TABLE `transfers` (
  `id` int(10) UNSIGNED NOT NULL,
  `nadawca` varchar(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nazwa_nad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adres_nad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kod_pocztowy_nad` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `miejscowosc_nad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kwota` decimal(20,2) NOT NULL,
  `typ` enum('wewnetrzny','miedzybankowy','ekspresowy') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tytul` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL,
  `odbiorca` varchar(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nazwa_odb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adres_odb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kod_pocztowy_odb` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `miejscowosc_odb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jawny` tinyint(1) NOT NULL,
  `status` enum('oczekuje na weryfikacje','w trakcie realizacji','wyslany','zrealizowany','odrzucony','przychodzacy-zrealizowany','przychodzacy-odrzucony') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `transfers`
--

INSERT INTO `transfers` (`id`, `nadawca`, `nazwa_nad`, `adres_nad`, `kod_pocztowy_nad`, `miejscowosc_nad`, `kwota`, `typ`, `tytul`, `odbiorca`, `nazwa_odb`, `adres_odb`, `kod_pocztowy_odb`, `miejscowosc_odb`, `jawny`, `status`, `created_at`, `updated_at`) VALUES
(1, '181324211543029349040207', 'Adam Abacki', 'ul. Kopernika 123', '35601', 'Rzeszów', '5.00', 'miedzybankowy', 'Drobne', '242434343434324233', 'Damian D', 'ul. Mickiewicza 321', '35213', 'Rzeszów', 1, 'w trakcie realizacji', '2021-02-03 12:57:30', NULL),
(2, '181324211543029349040207', 'X D', 'ul. Nazwa 3', '12321', 'Rzeszów', '10.00', 'miedzybankowy', 'Tak', '181324211543029349040207', 'P L', 'ul. Krótka 2', '35213', 'Rzeszów', 1, 'w trakcie realizacji', '2021-02-03 13:00:48', NULL),
(3, '181324211543029349040207', 'D D', 'ul. Test 3', '12212', 'Rzeszów', '142.00', 'miedzybankowy', '', '181324211543029349040207', 'M N', 'ul. Długa 3', '22321', 'Rzeszów', 1, 'przychodzacy-zrealizowany', '2021-02-03 13:28:54', NULL),
(4, '181324211543029349040207', 'P O', 'ul. Testowa 24', '32221', 'Rzeszów', '65.00', 'miedzybankowy', 'Zwrot', '181324211543029349040207', 'X D', 'ul. Jasna 4', '32123', 'Rzeszów', 1, 'przychodzacy-odrzucony', '2021-02-03 13:30:01', NULL);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_login_unique` (`login`),
  ADD UNIQUE KEY `clients_pesel_unique` (`PESEL`);

--
-- Indeksy dla tabeli `client_accounts`
--
ALTER TABLE `client_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_accounts_id_klienta_foreign` (`id_klienta`),
  ADD KEY `client_accounts_id_konta_foreign` (`id_konta`);

--
-- Indeksy dla tabeli `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `client_accounts`
--
ALTER TABLE `client_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT dla tabeli `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `client_accounts`
--
ALTER TABLE `client_accounts`
  ADD CONSTRAINT `client_accounts_id_klienta_foreign` FOREIGN KEY (`id_klienta`) REFERENCES `clients` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `client_accounts_id_konta_foreign` FOREIGN KEY (`id_konta`) REFERENCES `accounts` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
