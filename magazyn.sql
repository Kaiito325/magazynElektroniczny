-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 17 Mar 2025, 11:17
-- Wersja serwera: 10.4.27-MariaDB
-- Wersja PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `magazyn`
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
-- Zrzut danych tabeli `egzemplarze`
--

INSERT INTO `egzemplarze` (`id`, `id_przedmiotu`, `id_magazynu`, `stan`, `opis`, `ilosc`, `data_dodania`) VALUES
(1, 1, 0, 'Nowy', '', 1, '2025-03-16 23:00:00'),
(2, 3, 0, 'Używany', '', 1, '2025-03-17 09:05:00'),
(3, 2, 0, 'Używany', '', 1, '2025-03-17 09:05:11'),
(4, 4, 0, 'Używany', '', 1, '2025-03-17 09:05:20'),
(5, 5, 0, 'Nowy', '', 1000, '2025-03-17 09:05:34'),
(6, 6, 0, 'Nowy', '', 2000, '2025-03-17 09:05:52');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `kategorie`
--

INSERT INTO `kategorie` (`id`, `nazwa`) VALUES
(1, 'narzędzia_elektryczne'),
(2, 'narzędzia'),
(3, 'śruby');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `magazyny`
--

CREATE TABLE `magazyny` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) DEFAULT NULL,
  `lokalizacja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `magazyny`
--

INSERT INTO `magazyny` (`id`, `nazwa`, `lokalizacja`) VALUES
(0, 'główny', NULL),
(1, 'zapasowy', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmioty`
--

CREATE TABLE `przedmioty` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL,
  `opis` text NOT NULL,
  `zdjecie` varchar(50) NOT NULL,
  `id_kat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `przedmioty`
--

INSERT INTO `przedmioty` (`id`, `nazwa`, `opis`, `zdjecie`, `id_kat`) VALUES
(1, 'wiertarka BOSH ', 'wiertarka ', '', 1),
(2, 'młotek lekki', '', '', 2),
(3, 'młotek ciężki', '', '', 2),
(4, 'wiertarko-wkrętarka Parkside', '', '', 1),
(5, 'śruba 5x10', '', '', 3),
(6, 'śruba 5x15', '', '', 3);

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
  ADD KEY `id_magazynu` (`id_magazynu`);

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
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `egzemplarze`
--
ALTER TABLE `egzemplarze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `dziennik_zmian`
--
ALTER TABLE `dziennik_zmian`
  ADD CONSTRAINT `dziennik_zmian_ibfk_1` FOREIGN KEY (`id_egzemplarze`) REFERENCES `egzemplarze` (`id`),
  ADD CONSTRAINT `dziennik_zmian_ibfk_2` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownicy` (`id`);

--
-- Ograniczenia dla tabeli `egzemplarze`
--
ALTER TABLE `egzemplarze`
  ADD CONSTRAINT `egzemplarze_ibfk_1` FOREIGN KEY (`id_przedmiotu`) REFERENCES `przedmioty` (`id`),
  ADD CONSTRAINT `egzemplarze_ibfk_2` FOREIGN KEY (`id_magazynu`) REFERENCES `magazyny` (`id`);

--
-- Ograniczenia dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  ADD CONSTRAINT `przedmioty_ibfk_1` FOREIGN KEY (`id_kat`) REFERENCES `kategorie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
