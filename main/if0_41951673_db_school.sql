-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Počítač: sql313.infinityfree.com
-- Vytvořeno: Úte 26. kvě 2026, 12:50
-- Verze serveru: 11.4.11-MariaDB
-- Verze PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `if0_41951673_db_school`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `krouzky`
--

CREATE TABLE `krouzky` (
  `id` int(11) NOT NULL,
  `nazev` varchar(100) NOT NULL,
  `popis` text DEFAULT NULL,
  `ucitel_id` int(11) DEFAULT NULL,
  `max_kapacita` int(11) NOT NULL DEFAULT 20,
  `den_tydne` enum('Pondělí','Úterý','Středa','Čtvrtek','Pátek') NOT NULL,
  `cas_od` time NOT NULL,
  `cas_do` time NOT NULL,
  `mistnost` varchar(20) NOT NULL,
  `aktivni` tinyint(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `krouzky`
--

INSERT INTO `krouzky` (`id`, `nazev`, `popis`, `ucitel_id`, `max_kapacita`, `den_tydne`, `cas_od`, `cas_do`, `mistnost`, `aktivni`) VALUES
(1, 'Robotika', 'Stavime a programujeme roboty pomoci LEGO Mindstorms a Arduina. Naucis se zaklady elektroniky, programovani a konstruovani.', 3, 15, 'Pondělí', '14:00:00', '16:00:00', 'PC3', 1),
(2, 'Šachový kroužek', 'Naucime te zaklady sachove hry, taktiky i strategie. Vhodne pro zacatecniky i mirne pokrocile.', 4, 20, 'Středa', '13:30:00', '15:00:00', 'B204', 1),
(3, 'Programování', 'Zaklady programovani v Pythonu. Od prvniho hello world az po jednoduche hry.', 3, 12, 'Čtvrtek', '14:00:00', '15:30:00', 'PC2', 1),
(4, 'Sportovní aerobik', 'Tanec, kondice a zabava v jednom. Prijd si zasportovat.', 4, 25, 'Pondělí', '15:00:00', '16:15:00', 'GYM', 1),
(5, 'Čtenářský klub', 'Cteme zajimave knihy a diskutujeme o nich.', 4, 18, 'Pátek', '13:00:00', '14:00:00', 'B108', 1),
(6, 'Chemický krouzek', 'Bezpecne pokusy a zajimave experimenty z oblasti chemie.', 3, 10, 'Středa', '15:00:00', '16:30:00', 'LAB1', 1),
(8, 'Elektro technika', 'El technika s hoškem', 4, 5, 'Pátek', '12:35:00', '14:00:00', 'El 1', 1),
(9, 'Jízda na skútru', 'Základy jízdy na skútru v klidném prostředí školního parkoviště.', 3, 6, 'Středa', '15:25:00', '16:15:00', 'Parkoviště', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `prihlaseni`
--

CREATE TABLE `prihlaseni` (
  `id` int(11) NOT NULL,
  `zak_id` int(11) NOT NULL,
  `krouzek_id` int(11) NOT NULL,
  `datum_prihlaseni` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `prihlaseni`
--

INSERT INTO `prihlaseni` (`id`, `zak_id`, `krouzek_id`, `datum_prihlaseni`) VALUES
(15, 1, 1, '2026-04-28 13:13:30'),
(2, 1, 3, '2026-04-22 18:34:54'),
(3, 2, 2, '2026-04-22 18:34:54'),
(4, 2, 5, '2026-04-22 18:34:54'),
(18, 10, 9, '2026-05-19 17:03:26'),
(12, 2, 1, '2026-04-28 13:10:32'),
(13, 2, 3, '2026-04-28 13:10:35'),
(14, 1, 8, '2026-04-28 13:13:15'),
(16, 1, 9, '2026-05-19 06:32:54');

-- --------------------------------------------------------

--
-- Struktura tabulky `uzivatele`
--

CREATE TABLE `uzivatele` (
  `id` int(11) NOT NULL,
  `uzivatelske_jmeno` varchar(50) NOT NULL,
  `heslo` varchar(255) NOT NULL,
  `cele_jmeno` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('zak','ucitel','admin') NOT NULL DEFAULT 'zak',
  `trida` varchar(10) DEFAULT NULL,
  `tema` varchar(10) DEFAULT 'svetly',
  `jazyk` varchar(10) DEFAULT 'cs',
  `vytvoren` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `uzivatele`
--

INSERT INTO `uzivatele` (`id`, `uzivatelske_jmeno`, `heslo`, `cele_jmeno`, `email`, `role`, `trida`, `tema`, `jazyk`, `vytvoren`) VALUES
(1, 'zak1', '$2y$10$WT/DgpNKQFc2TVbCEc3BR..hE36ePp3mWG3fz7Xb3sx/Y.WIi/Eby', 'Jan Novak', 'jan.novak@skola.cz', 'zak', '3.A', 'svetly', 'cs', '2026-04-22 18:34:54'),
(2, 'zak2', '$2y$10$WT/DgpNKQFc2TVbCEc3BR..hE36ePp3mWG3fz7Xb3sx/Y.WIi/Eby', 'Marie Svobodova', 'marie.svobodova@skola.cz', 'zak', '2.B', 'svetly', 'cs', '2026-04-22 18:34:54'),
(3, 'ucitel1', '$2y$10$WT/DgpNKQFc2TVbCEc3BR..hE36ePp3mWG3fz7Xb3sx/Y.WIi/Eby', 'Mgr. Petr Kratochvil', 'p.kratochvil@skola.cz', 'ucitel', NULL, 'svetly', 'cs', '2026-04-22 18:34:54'),
(4, 'ucitel2', '$2y$10$WT/DgpNKQFc2TVbCEc3BR..hE36ePp3mWG3fz7Xb3sx/Y.WIi/Eby', 'Ing. Jana Horackova', 'j.horackova@skola.cz', 'ucitel', NULL, 'svetly', 'cs', '2026-04-22 18:34:54'),
(5, 'admin', '$2y$10$WT/DgpNKQFc2TVbCEc3BR..hE36ePp3mWG3fz7Xb3sx/Y.WIi/Eby', 'Administrator', 'admin@skola.cz', 'admin', NULL, 'svetly', 'cs', '2026-04-22 18:34:54'),
(7, 'Vomacka', '$2y$10$dKWR7.4HuRRPQ7F6BxdW2.WTQz.VscrnCTtPQryBxcaCpZaXU5fw.', 'Jirka Vomacka', 'vomacka.jirka@skola.cz', 'zak', '4.A', 'svetly', 'cs', '2026-04-27 14:06:42'),
(10, 'zak_test', '$2y$10$od2GPzBdkf9JXxtQ6n3a2udoGZoS2QMpV3fWiAyQ5gen25kceFbTq', 'Antonín Novotný', 'novotny.antonin@skola.cz', 'zak', '5.C', 'svetly', 'cs', '2026-05-19 16:34:18');

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `krouzky`
--
ALTER TABLE `krouzky`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ucitel_id` (`ucitel_id`);

--
-- Klíče pro tabulku `prihlaseni`
--
ALTER TABLE `prihlaseni`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_prihlaseni` (`zak_id`,`krouzek_id`),
  ADD KEY `krouzek_id` (`krouzek_id`);

--
-- Klíče pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uzivatelske_jmeno` (`uzivatelske_jmeno`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `krouzky`
--
ALTER TABLE `krouzky`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pro tabulku `prihlaseni`
--
ALTER TABLE `prihlaseni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
