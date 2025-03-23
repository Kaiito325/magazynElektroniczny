-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2025 at 09:40 PM
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
-- Database: `magazyn`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dziennik_zmian`
--

CREATE TABLE `dziennik_zmian` (
  `id` int(11) NOT NULL,
  `id_egzemplarze` int(11) DEFAULT NULL,
  `id_uzytkownika` int(11) DEFAULT NULL,
  `akcja` varchar(30) DEFAULT NULL,
  `opis` text DEFAULT NULL,
  `data_zmiany` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `egzemplarze`
--

CREATE TABLE `egzemplarze` (
  `id` int(11) NOT NULL,
  `id_przedmiotu` int(11) NOT NULL,
  `id_magazynu` int(11) NOT NULL,
  `stan` varchar(20) NOT NULL DEFAULT 'Nowy',
  `opis` text NOT NULL,
  `ilosc` int(11) NOT NULL DEFAULT 1,
  `data_dodania` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `egzemplarze`
--

INSERT INTO `egzemplarze` (`id`, `id_przedmiotu`, `id_magazynu`, `stan`, `opis`, `ilosc`, `data_dodania`) VALUES
(1, 1, 1, 'Nowy', '', 1, '2025-03-16 23:00:00'),
(2, 3, 1, 'Używany', '', 1, '2025-03-17 09:05:00'),
(3, 2, 1, 'Używany', '', 1, '2025-03-17 09:05:11'),
(4, 4, 1, 'Używany', '', 1, '2025-03-17 09:05:20'),
(5, 5, 1, 'Nowy', '', 1000, '2025-03-17 09:05:34'),
(6, 6, 1, 'Nowy', '', 2000, '2025-03-17 09:05:52'),
(7, 8, 2, 'Nowy', '', 10, '2025-03-23 19:27:16'),
(8, 9, 2, 'Nowy', '', 5, '2025-03-23 19:34:03'),
(9, 10, 2, 'Nowy', '', 2, '2025-03-23 19:43:40'),
(10, 11, 1, 'Nowy', '', 20, '2025-03-23 19:57:09');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategorie`
--

INSERT INTO `kategorie` (`id`, `nazwa`) VALUES
(1, 'brak'),
(2, 'narzędzia_elektryczne'),
(3, 'narzędzia'),
(4, 'śruby');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `magazyny`
--

CREATE TABLE `magazyny` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) DEFAULT NULL,
  `zdjecie` varchar(60) DEFAULT NULL,
  `lokalizacja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `magazyny`
--

INSERT INTO `magazyny` (`id`, `nazwa`, `zdjecie`, `lokalizacja`) VALUES
(1, 'główny', 'upload/67e06ffb3bbb2_obraz_2025-03-23_213239922.png', 'Warszawa - centrum'),
(2, 'zapasowy - Kraków', 'upload/67e07069b5aaf_obraz_2025-03-23_213433510.png', 'Kraków - centrum');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmioty`
--

CREATE TABLE `przedmioty` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL,
  `opis` text NOT NULL,
  `zdjecie` varchar(60) NOT NULL,
  `id_kat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `przedmioty`
--

INSERT INTO `przedmioty` (`id`, `nazwa`, `opis`, `zdjecie`, `id_kat`) VALUES
(1, 'wiertarka BOSH ', 'wiertarka ', '', 2),
(2, 'młotek lekki', '', '', 3),
(3, 'młotek ciężki', '', '', 3),
(4, 'wiertarko-wkrętarka Parkside', '', '', 2),
(5, 'śruba 5x10', '', '', 4),
(6, 'śruba 5x15', '', '', 4),
(7, 'śruba 7x15', '', '../images/product.png', 4),
(8, 'kabel Rj-45 10m', '', '', 1),
(9, 'kabel zasilający C13', '', 'upload/67e0622b1b975_obraz_2025-03-23_203337282.png', 1),
(10, 'przewód 3x2,5 100m', '', 'upload/67e0646c2d6c7_obraz_2025-03-23_204321254.png', 1),
(11, 'ESP8266 NodeMcu v3', '', 'upload/67e067956f930_obraz_2025-03-23_205700830.png', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(11) NOT NULL,
  `imie` varchar(20) DEFAULT NULL,
  `nazwisko` varchar(20) DEFAULT NULL,
  `login` varchar(35) DEFAULT NULL,
  `haslo` varchar(50) DEFAULT NULL,
  `uprawnienia` enum('0','1','2') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `dziennik_zmian`
--
ALTER TABLE `dziennik_zmian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_egzemplarze` (`id_egzemplarze`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`);

--
-- Indeksy dla tabeli `egzemplarze`
--
ALTER TABLE `egzemplarze`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_przedmiotu` (`id_przedmiotu`),
  ADD KEY `egzemplarze_ibfk_2` (`id_magazynu`);

--
-- Indeksy dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `magazyny`
--
ALTER TABLE `magazyny`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kat` (`id_kat`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `egzemplarze`
--
ALTER TABLE `egzemplarze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `magazyny`
--
ALTER TABLE `magazyny`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `przedmioty`
--
ALTER TABLE `przedmioty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dziennik_zmian`
--
ALTER TABLE `dziennik_zmian`
  ADD CONSTRAINT `dziennik_zmian_ibfk_1` FOREIGN KEY (`id_egzemplarze`) REFERENCES `egzemplarze` (`id`),
  ADD CONSTRAINT `dziennik_zmian_ibfk_2` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id`);

--
-- Constraints for table `egzemplarze`
--
ALTER TABLE `egzemplarze`
  ADD CONSTRAINT `egzemplarze_ibfk_1` FOREIGN KEY (`id_przedmiotu`) REFERENCES `przedmioty` (`id`),
  ADD CONSTRAINT `egzemplarze_ibfk_2` FOREIGN KEY (`id_magazynu`) REFERENCES `magazyny` (`id`);

--
-- Constraints for table `przedmioty`
--
ALTER TABLE `przedmioty`
  ADD CONSTRAINT `przedmioty_ibfk_1` FOREIGN KEY (`id_kat`) REFERENCES `kategorie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
