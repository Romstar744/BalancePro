-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Ноя 26 2024 г., 12:28
-- Версия сервера: 10.11.10-MariaDB-ubu2204
-- Версия PHP: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `starostin_Bears`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, '12345', '$2y$10$u90c3UhbPZNehrmIYUlmj.9UkiQXFlJrUIxl7YrWLMoR3Q7MewSyu'),
(3, '123456', '$2y$10$xXB2v3OleUkZhy3pyJ0buOSPtFiIdJbsaIB/xmMERUaPGfUY4OXCS'),
(4, 'LILSPAL', '$2y$10$5YhpkZe06tTY7Qy/9/ZDTeDQ0uIuooL3DXw/SNc.8gy/HSPw4bz8C'),
(5, '1', '$2y$10$aTd51/StB4Gq2h04W43WN.hRTze4gaBmAb3ShxM3baGtp5ZgPlvGy'),
(6, '11', '$2y$10$OuYtLYqfVfysLwsCWqcOn.Z1kWlul1RpNBxnSECEs3ABzBjxDKmTC'),
(7, '123', '$2y$10$Tg9.L/FNNdWGSiK4k8Slp.46T/oXWi5iiZFTkzIU3K9CRGoYcoxji'),
(8, 'kkk111', '$2y$10$U68LCuGeTLeIJYGvaGOXf.nbUzwfSch7aGWiCo54/CPrqUR6cEoGS'),
(9, 'тест', '$2y$10$VlOblY9QuEycu8JSgIukh.tr2Q4XsvzZMNL4MaxOflnQP0PdHgVh6'),
(10, 'тест1', '$2y$10$8E.Vue6s83c3S.l0QNE6wO/1jXZk2S/d07pi/dRnweQXzOM3YH7y.'),
(11, 'тест12', '$2y$10$CaTI/kPbYEly0PifoNvK5eLp4oPoWoJw2pe2PeWd5l/.otYd0NVxK'),
(12, 'Рро', '$2y$10$PrFuRvkZZN/Spe2d5O3RGeVGzbTecKeLXjg2x5zQHZkMEuxLFF/eK'),
(13, 'Рр', '$2y$10$W.ZOAfrAzl5BgLf6EL8Ab.TilLiB83gkTqqD3QTroZIAl2bAkYvmK'),
(14, 'Haha', '$2y$10$cJkICUgl0xEX7HzEP1GcuO3Q8CVGOaRsIwux.JHLYuaUmqnBIBZHG'),
(15, 'Mishashiska', '$2y$10$8UBErBdKQ/nIHPmK5CvEiuYKc0Kmt8KHPTk9QObA9Zp736wtL/TBm');

-- --------------------------------------------------------

--
-- Структура таблицы `athletes`
--

CREATE TABLE `athletes` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `patronymic` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `athletes`
--

INSERT INTO `athletes` (`id`, `username`, `password`, `first_name`, `last_name`, `patronymic`, `birthdate`) VALUES
(16, 'LILSPAL', '$2y$10$NKkuJ17A/Zb7oRt05WOCueBx1uLsfJSWxwdd.NzjFg9VRW9yB15om', 'Роман', 'Старостин', 'Александрович', '2005-02-20'),
(17, 'LILPOSPAL', '$2y$10$oGvOfPCC3pxewfsxWmudh.6jJiJpKZtKnvDAdkfTi52vvQ3ZNfilO', 'Иванов', 'Иванович', 'Иванович', '2017-06-07'),
(18, 'kkk111', '$2y$10$9.xD2RmYnXnkZx30zr8NA.0nwcYyrBY4H25BiKrighRocULE7GBze', 'Коровин', 'Роман', 'Александрович', '2024-05-20'),
(19, 'тест', '$2y$10$AeZg6yeER2NLVdXU8Gz9nur8voeFZiMWItaxxH0ib2ynK0QDjDkCa', 'тест', 'тест', 'тест', '2024-11-13'),
(20, 'test222', '$2y$10$vVJXNld88DsMaa/Qoe8Pie8HpHmSF6LWCui4ySWZUrG7qS2T8bKgy', 'Коровин', 'Роман', 'Александрович', '2024-11-05'),
(21, 'LILSPAL123', '$2y$10$1MAfRP/qu7BIQzazFocreegqS9jZzK2NFIQCyngws6DA/IihTcoUi', 'Спортстмен', 'Спортсменов', 'Спортсменович', '2024-08-06'),
(22, 'govor', '$2y$10$n2RNmffW6vj4cm9lnf7Pa.PU9ZqN.9oULiP.1n3UECGQGzlhT0q2C', 'Елена', 'Самойлова', 'Олеговна', '2002-08-12'),
(23, 'Итт', '$2y$10$zcTscci1OKOKL347kCZkTu3TzaQSYPL2ckrJnnMOSDpBP9xbhoDzu', 'Ть', 'Тть', 'Ьть', '2024-11-14'),
(24, 'Haha', '$2y$10$G2/3BGdzVkAmG27zYs.n2u2MFCds9MDpIL9dgr80prP98JDfWtAFi', 'Леонид', 'Павлов', 'Ильич', '2024-11-21'),
(25, '1111', '$2y$10$/g4WX7fsNAQKwszYYXcNhOdWw41o.XDH4bdL43Lx6QJhsOeRCrjla', '1111', '1211', '11', '2024-11-08'),
(26, '383uheh', '$2y$10$VXoGDy3MdNkLBcuLIZerA.8CgeBUOpdN8ZoKYL2LFoeERgWTb4PPi', 'ueu3he', 'hehebe', 'ueehv', '2024-11-13'),
(27, 'chichxh', '$2y$10$C8dF2XXQ/8jCgY5trsNa9uyM26e6wvwdVF0ObBPv9nu0PZQaViR7m', 'Дайаана', 'Егорова', 'Андреева', '1999-11-24'),
(28, 'gdf', '$2y$10$Lb8CaPPCe/VSKYfJdy8Vs.8U8WFOiTYmnzTpGZsCO/9sAT/HBMOHe', 'gdfg', 'dfgdfg', 'dfgdf', '2024-06-04');

-- --------------------------------------------------------

--
-- Структура таблицы `athlete_availability`
--

CREATE TABLE `athlete_availability` (
  `id` int(11) NOT NULL,
  `athlete_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_interval` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `athlete_availability`
--

INSERT INTO `athlete_availability` (`id`, `athlete_id`, `date`, `time_interval`) VALUES
(35, 21, '2025-07-11', '13:20-17:20'),
(36, 27, '2024-11-24', '14:30-15:30'),
(37, 27, '2024-11-24', '12:22-12:23'),
(38, 16, '2024-11-30', '14:00-16:00'),
(39, 16, '2025-01-31', '12:00-15:00');

-- --------------------------------------------------------

--
-- Структура таблицы `coaches`
--

CREATE TABLE `coaches` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `patronymic` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `coaches`
--

INSERT INTO `coaches` (`id`, `first_name`, `last_name`, `patronymic`, `username`, `password`, `phone`) VALUES
(1, 'Михаил', 'Петров', 'Сидорович', 'kkk111', '$2y$10$ZZFtHCuX7sb7Wa79hhhVueAmlk5Q6J24.kR4cGn.QTBWQe.CxlOrG', '89219790382'),
(2, '1234', 'Григорий', 'Палыч', 'палый', '$2y$10$w1E7b6aqHzmAFWGQDzcQT.oX6PJnr1vUYPgvrmlaXB452RodWFE2W', '89623456655'),
(3, 'ппп', 'ппп', 'ппп', 'ppp', '$2y$10$GhMCYIs1mFfpkKOP3tPMDO00c2yIgnTtSI.leKuaY6ZI8FKWqDcqe', '88888888888'),
(4, 'Роман', 'Коровин', 'Петрович', 'kkk222', '$2y$10$8/0687auYOneFehGWOlaq.mX0/zVxGGxfRbkpaG6a4KYqIJu8LfR.', '8882223456'),
(5, 'Коровин', 'Роман', 'Александрович', 'LILSPAL', '$2y$10$wXeJHadChnu9M8lFtx3SsOL8R/VXN1D8.ItMQi0LbU0gCTdxBda..', '89219790382'),
(6, 'Роман', 'Ромашкин', 'Романович', 'LILPOSPAL', '$2y$10$jGEiLNXv4tsMePAvfMDcBe6Cm9IJ88T9JVdos.mEYrJs/Qj0as84i', '8882223456'),
(7, 'Тест', 'Тест', 'Тест', 'Тест', '$2y$10$sTwdCPHkNMiIiDQGYptU9uRxCyEpZOSQlWneQQZRVVsiAk9XcvEiK', 'тест'),
(8, 'Test', 'Test', 'Testovich', 'test222', '$2y$10$hGfLd6l03LVPY5QaPnczauMwlXubQSzsWqs9j2uAYTcosN9/sxnIG', '89218789456'),
(9, 'Петр', 'Петров', 'Петрович', 'Петр', '$2y$10$Ag7pI74SGoPKsXlx9lrPtu08JsgWWZXJV5Pai7G/kQgKcCZaIP3jm', '+79217777777'),
(10, 'Петр', 'Петр', 'Петр', 'Петр_1', '$2y$10$.0LtrKG6jIK7xu4rvUvdTueKGnenJpbewr6s2a3BEP7Wn6itw75PS', '+79218888888'),
(11, 'Леонид', 'Павлов', 'Отчество', 'Haha', '$2y$10$URLN8Q8omvUGC6Ra/ieViuIEixF75Y6HGQEVM/4klW2gqvVm6pemW', '89214207398'),
(12, 'Леонид', 'Павлов', 'Ильич', '776', '$2y$10$AsKVvkucwsp1h6emUQgiLuG0f3UnfKYMgtFPMhxV6/AyBScuaT4Qu', '+79214207398'),
(13, 'Коровин', 'Роман', 'Александрович', 'LILSPAL1111', '$2y$10$IOiVm6.uPOtkhgN67vMylOWrzqcLLpNTtrq778yODD6lSicj9WOpa', '48538432848'),
(14, 'писька', 'хуй', 'пися попа', 'piska23cm', '$2y$10$lALgj8CRgmCyQYI4.LC46emvAbO4XrJVg1nxEDS6qdCHa/s5AZYJS', '88005553535'),
(15, 'Борис', 'Заикин', 'Сергеевич', 'Authorisen Codres', '$2y$10$QOhndQ1L54ZHajSjSOi.f.4hGOsb81RqiXZgGCfyM45Vl2Q2l503O', '9533656920');

-- --------------------------------------------------------

--
-- Структура таблицы `coach_athlete`
--

CREATE TABLE `coach_athlete` (
  `coach_id` int(11) NOT NULL,
  `athlete_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `coach_athlete_assignments`
--

CREATE TABLE `coach_athlete_assignments` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `athlete_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_interval` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `coach_athlete_assignments`
--

INSERT INTO `coach_athlete_assignments` (`id`, `coach_id`, `athlete_id`, `date`, `time_interval`) VALUES
(26, 5, 21, '2025-07-11', NULL),
(27, 5, 21, '2025-07-11', NULL),
(28, 11, 21, '2025-07-11', NULL),
(29, 11, 21, '2025-07-11', NULL),
(30, 13, 21, '2025-07-11', NULL),
(31, 15, 27, '2024-11-24', NULL),
(32, 15, 27, '2024-11-24', NULL),
(33, 5, 16, '2024-11-30', NULL),
(34, 5, 16, '2024-11-30', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `tournament` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `team` varchar(255) DEFAULT NULL,
  `team1` varchar(255) DEFAULT NULL,
  `team2` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `matches`
--

INSERT INTO `matches` (`id`, `tournament`, `date`, `time`, `team`, `team1`, `team2`) VALUES
(1, 'КВЛ', '2024-10-12', '11:50:00', 'ЧМ-Политех 1ж', NULL, NULL),
(2, 'КВЛ', '2024-10-13', '14:00:00', 'ЧМ-Политех 1ж', NULL, NULL),
(3, 'КВЛ', '2024-10-18', '16:00:00', 'ЧМ-Политех 1м', NULL, NULL),
(4, 'КВЛ', '2024-10-19', '18:50:00', 'ЧМ-Политех 1м', NULL, NULL),
(5, 'КВЛ', '2024-10-07', '18:30:00', 'ЧМ-Политех 2ж', NULL, NULL),
(6, 'КВЛ', '2024-10-12', '18:45:00', 'ЧМ-Политех 2ж', NULL, NULL),
(7, 'КВЛ', '2024-10-13', '20:20:00', 'ЧМ-Политех 3м', NULL, NULL),
(8, 'КВЛ', '2024-10-19', '20:20:00', 'ЧМ-Политех 3м', NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Индексы таблицы `athletes`
--
ALTER TABLE `athletes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Индексы таблицы `athlete_availability`
--
ALTER TABLE `athlete_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `athlete_id` (`athlete_id`);

--
-- Индексы таблицы `coaches`
--
ALTER TABLE `coaches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Индексы таблицы `coach_athlete`
--
ALTER TABLE `coach_athlete`
  ADD PRIMARY KEY (`coach_id`,`athlete_id`),
  ADD KEY `athlete_id` (`athlete_id`);

--
-- Индексы таблицы `coach_athlete_assignments`
--
ALTER TABLE `coach_athlete_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coach_id` (`coach_id`),
  ADD KEY `athlete_id` (`athlete_id`);

--
-- Индексы таблицы `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `athletes`
--
ALTER TABLE `athletes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `athlete_availability`
--
ALTER TABLE `athlete_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `coaches`
--
ALTER TABLE `coaches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `coach_athlete_assignments`
--
ALTER TABLE `coach_athlete_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT для таблицы `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `athlete_availability`
--
ALTER TABLE `athlete_availability`
  ADD CONSTRAINT `athlete_availability_ibfk_1` FOREIGN KEY (`athlete_id`) REFERENCES `athletes` (`id`);

--
-- Ограничения внешнего ключа таблицы `coach_athlete`
--
ALTER TABLE `coach_athlete`
  ADD CONSTRAINT `coach_athlete_ibfk_1` FOREIGN KEY (`coach_id`) REFERENCES `coaches` (`id`),
  ADD CONSTRAINT `coach_athlete_ibfk_2` FOREIGN KEY (`athlete_id`) REFERENCES `athletes` (`id`);

--
-- Ограничения внешнего ключа таблицы `coach_athlete_assignments`
--
ALTER TABLE `coach_athlete_assignments`
  ADD CONSTRAINT `coach_athlete_assignments_ibfk_1` FOREIGN KEY (`coach_id`) REFERENCES `coaches` (`id`),
  ADD CONSTRAINT `coach_athlete_assignments_ibfk_2` FOREIGN KEY (`athlete_id`) REFERENCES `athletes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
