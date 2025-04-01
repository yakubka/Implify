-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 01 2025 г., 17:41
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `volunteering`
--

-- --------------------------------------------------------

--
-- Структура таблицы `offers`
--

CREATE TABLE `offers` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `country` varchar(50) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `offers`
--

INSERT INTO `offers` (`id`, `user_id`, `title`, `description`, `country`, `image_path`, `created_at`) VALUES
(5, 1, 'Помощь в детском доме', 'Нужны волонтеры для организации мероприятий в детском доме', 'Russia', 'images/offer1.jpg', '2025-03-26 12:43:59'),
(6, 2, 'Экологическая акция', 'Уборка парка и посадка деревьев', 'USA', 'images/offer2.jpg', '2025-03-26 12:43:59'),
(7, 3, 'Обучение пожилых людей', 'Помощь в освоении компьютера и интернета', 'Germany', 'images/offer3.jpg', '2025-03-26 12:43:59'),
(8, 1, 'Фестиваль волонтеров', 'Организация городского фестиваля', 'Russia', 'images/offer4.jpg', '2025-03-26 12:43:59'),
(9, 3, 'Помощь собачкам', 'ну типа лол они тоже нуждаются в помощи', 'France', 'uploads/offers/9.jpg', '2025-03-26 13:50:46'),
(10, 1, 'Зелёный Город', 'Описание:\r\n\"Зелёный Город\" – это волонтёрская экологическая программа, направленная на озеленение городских территорий, уборку парков и популяризацию экосознательного образа жизни. Участники помогают высаживать деревья, проводить субботники и обучать местных жителей раздельному сбору отходов.\r\n\r\nЦели программы:\r\nОзеленение и благоустройство парков и дворов.\r\nОрганизация субботников и уборки мусора.\r\nПроведение мастер-классов по раздельному сбору отходов.\r\nСоздание городских садов и экозон для отдыха.\r\n\r\nКто может участвовать?\r\nСтуденты, школьники, активные горожане.\r\n\r\nКомпании, желающие внести вклад в экологию.\r\n\r\nВолонтёры без ограничений по возрасту.\r\n\r\nФормат участия:\r\n\r\nРазовые акции (субботники, посадка деревьев).\r\n\r\nДолгосрочные проекты (уход за зелёными зонами).\r\n\r\nОбразовательные лекции и воркшопы.\r\n\r\nГде проходит?\r\nВ парках, скверах и дворах города.\r\n\r\nКак присоединиться?\r\nЗаполнить заявку на сайте и выбрать удобный формат участия!', 'Узбекистан', 'uploads/offers/10.jpg', '2025-03-27 04:28:25'),
(11, 3, 'Дорога к знаниям', '\"Дорога к знаниям\" – это образовательная волонтёрская программа, направленная на помощь детям из малообеспеченных семей, сиротских учреждений и сельских школ. Волонтёры проводят занятия, помогают с домашними заданиями и организуют мероприятия, развивающие любознательность и мотивацию к обучению.\r\n\r\nЦели программы:\r\n\r\nПомощь детям в освоении школьной программы\r\n\r\nПоддержка подростков в выборе профессии и подготовке к экзаменам\r\n\r\nРазвитие критического мышления и творческих навыков\r\n\r\nПовышение доступности качественного образования\r\n\r\nКто может участвовать?\r\n\r\nСтуденты педагогических вузов и преподаватели\r\n\r\nСпециалисты, желающие передавать знания в своей области\r\n\r\nВсе, кто готов уделять время детям и помогать им развиваться\r\n\r\nФорматы участия:\r\n\r\nОнлайн-уроки и консультации\r\n\r\nОчные занятия в детских домах и школах\r\n\r\nОрганизация образовательных мастер-классов и конкурсов\r\n\r\nГде проходит?\r\nВ школах, детских домах, общественных центрах и онлайн\r\n\r\nКак присоединиться?\r\nНеобходимо подать заявку на участие, пройти короткое обучение и выбрать удобный формат работы с детьми.', 'Узбекистан', 'uploads/offers/11.jpg', '2025-03-27 04:51:48'),
(12, 1, 'Репетитор для детей из малообеспеченных семей', 'Мы ищем волонтёров, которые готовы бесплатно заниматься с детьми, у которых нет возможности оплачивать дополнительные уроки. Чаще всего помощь требуется в математике, английском, русском языке и других школьных предметах. Формат занятий может быть как очным, так и онлайн. Главное — терпение, умение доступно объяснять материал и желание помочь ребёнку почувствовать уверенность в своих знаниях.', 'Узбекистан', 'uploads/offers/12.jpg', '2025-03-27 16:56:42');

-- --------------------------------------------------------

--
-- Структура таблицы `offer_responses`
--

CREATE TABLE `offer_responses` (
  `id` int NOT NULL,
  `offer_id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `offer_responses`
--

INSERT INTO `offer_responses` (`id`, `offer_id`, `user_id`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 'я считаю то что мы обязаны природе и по этой причине я хочу помочь вашей волонтерской програме', 'accepted', '2025-03-27 04:40:28', '2025-03-27 04:47:13'),
(2, 11, 1, 'Я считаю то что все имеют право на обучение и по этой причине я хочу вступить в вашу волонтерскую программу', 'accepted', '2025-03-27 04:53:49', '2025-03-27 04:58:35'),
(3, 11, 2, 'бедные детишки я хочу что бы они учились :)', 'rejected', '2025-03-27 04:54:49', '2025-03-27 04:58:32'),
(4, 10, 2, 'Хочу обратиться ко всем, кто неравнодушен к окружающему миру, кто готов помогать другим и менять жизнь к лучшему. Волонтёрство — это не просто участие в социальных проектах, а особая философия, основанная на доброте, взаимопомощи и желании делать этот мир лучше.\r\n\r\nСегодня, когда общество сталкивается с различными вызовами, особенно важно объединять усилия для поддержки тех, кто в этом нуждается. Детские дома, больницы, приюты для животных, экологические организации — все они нуждаются в людях, готовых вложить свою энергию, знания и время в помощь окружающим.\r\n\r\nКаждый из нас может стать частью большого доброго дела. Кто-то помогает детям освоить школьные предметы, кто-то ухаживает за больными и пожилыми людьми, а кто-то сажает деревья и заботится о природе. Нет маленькой помощи — любое доброе дело имеет значение.\r\n\r\nВолонтёрство — это не только возможность отдавать, но и уникальный опыт, который помогает развиваться, находить новых друзей, учиться работать в команде и даже строить свою карьеру. Многие организации ценят опыт волонтёрской работы, ведь он говорит о человеке больше, чем сухие строчки резюме.\r\n\r\nМы приглашаем всех желающих присоединиться к нашим инициативам. Независимо от возраста, профессии и жизненного опыта — у каждого есть шанс внести свой вклад. Вы можете участвовать в мероприятиях, делиться своими идеями, обучать, помогать, организовывать. Главное — не оставаться в стороне.\r\n\r\nЕсли у вас есть желание стать частью волонтёрского движения, мы будем рады видеть вас среди наших единомышленников. Вместе мы сможем сделать гораздо больше, чем поодиночке.', 'accepted', '2025-03-27 05:33:45', '2025-03-27 05:34:47');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `country` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `country`, `created_at`) VALUES
(1, 'NIbragim', 'xaytan1.on@gmail.com', '$2y$10$yEATAe8RKm7bfaNi0zCbReQdWP4kno6RE9CEoALSJiwCNNrAZAlf2', 'Узбекистан', '2025-03-25 20:48:29'),
(2, 'yusupovJ10', 'ibrokhimnurullaev2010@gmail.com', '$2y$10$sEV3MWsjHNDIq9Rjsie2QOe1RB3u7UREOlBNwqQPvrgU3/bAfx0Wi', 'Беларусь', '2025-03-26 13:06:14'),
(3, 'psiblades', 'psi.blades@gmail.com', '$2y$10$qIzCUAv3PAVKs1ZUd4tFquzMkCo31RiMLQM0VCAGMN.iuuCuzutXq', 'Центральноафриканская Республика', '2025-03-26 14:11:56'),
(5, 'Ivan Petrov', 'ivan.petrov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(6, 'Anna Smirnova', 'anna.smirnova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(7, 'Sergey Ivanov', 'sergey.ivanov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(8, 'Elena Kuznetsova', 'elena.kuznetsova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(9, 'Dmitry Volkov', 'dmitry.volkov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(10, 'Olga Sokolova', 'olga.sokolova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(11, 'Alexey Morozov', 'alexey.morozov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(12, 'Natalia Petrova', 'natalia.petrova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(13, 'Andrey Fedorov', 'andrey.fedorov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(14, 'Tatiana Ivanova', 'tatiana.ivanova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(15, 'Pavel Novikov', 'pavel.novikov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(16, 'Maria Volkova', 'maria.volkova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(17, 'Viktor Zaitsev', 'viktor.zaitsev@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(18, 'Yulia Pavlova', 'yulia.pavlova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(19, 'Mikhail Orlov', 'mikhail.orlov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(20, 'Ekaterina Nikitina', 'ekaterina.nikitina@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(21, 'Artem Lebedev', 'artem.lebedev@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(22, 'Svetlana Romanova', 'svetlana.romanova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(23, 'Konstantin Vasiliev', 'konstantin.vasiliev@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(24, 'Anastasia Egorova', 'anastasia.egorova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(25, 'Denis Popov', 'denis.popov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(26, 'Alina Sorokina', 'alina.sorokina@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(27, 'Vladimir Sokolov', 'vladimir.sokolov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(28, 'Daria Mikhailova', 'daria.mikhailova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(29, 'Anton Frolov', 'anton.frolov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(30, 'Valentina Zakharova', 'valentina.zakharova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(31, 'Roman Belyaev', 'roman.belyaev@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(32, 'Veronika Kozlova', 'veronika.kozlova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(33, 'Grigory Medvedev', 'grigory.medvedev@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(34, 'Larisa Semenova', 'larisa.semenova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(35, 'Nikolay Vorobyov', 'nikolay.vorobyov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(36, 'Galina Pavlova', 'galina.pavlova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(37, 'Vadim Gusev', 'vadim.gusev@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(38, 'Margarita Vinogradova', 'margarita.vinogradova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(39, 'Stanislav Bogdanov', 'stanislav.bogdanov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(40, 'Lyubov Morozova', 'lyubov.morozova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(41, 'Timur Nikolaev', 'timur.nikolaev@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(42, 'Raisa Polyakova', 'raisa.polyakova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(43, 'Yaroslav Tikhonov', 'yaroslav.tikhonov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(44, 'Lydia Fomina', 'lydia.fomina@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(45, 'Oleg Davydov', 'oleg.davydov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(46, 'Zoya Belyaeva', 'zoya.belyaeva@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(47, 'Fedor Makarov', 'fedor.makarov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(48, 'Nina Gorbacheva', 'nina.gorbacheva@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(49, 'Vitaly Komarov', 'vitaly.komarov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(50, 'Tamara Voronova', 'tamara.voronova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(51, 'Boris Zhukov', 'boris.zhukov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(52, 'Klara Kuzmina', 'klara.kuzmina@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(53, 'Gennady Baranov', 'gennady.baranov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(54, 'Vera Savina', 'vera.savina@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(55, 'Leonid Gromov', 'leonid.gromov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(56, 'Rosa Tarasova', 'rosa.tarasova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(57, 'Vasily Kiselev', 'vasily.kiselev@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(58, 'Sofia Orlova', 'sofia.orlova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(59, 'Arkady Efimov', 'arkady.efimov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(60, 'Larisa Zhukova', 'larisa.zhukova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(61, 'Yakov Petrov', 'yakov.petrov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(62, 'Nadezhda Semyonova', 'nadezhda.semyonova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(63, 'German Fomin', 'german.fomin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(64, 'Raisa Zimina', 'raisa.zimina@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(65, 'Valentin Gorbunov', 'valentin.gorbunov@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(66, 'Ludmila Krylova', 'ludmila.krylova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(67, 'Anatoly Voronin', 'anatoly.voronin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(68, 'Zinaida Romanova', 'zinaida.romanova@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Russia', '2025-03-26 14:32:27'),
(69, 'John Smith', 'john.smith@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(70, 'Emily Johnson', 'emily.johnson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(71, 'Michael Williams', 'michael.williams@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(72, 'Sarah Brown', 'sarah.brown@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(73, 'David Jones', 'david.jones@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(74, 'Jessica Garcia', 'jessica.garcia@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(75, 'Robert Miller', 'robert.miller@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(76, 'Jennifer Davis', 'jennifer.davis@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(77, 'Daniel Rodriguez', 'daniel.rodriguez@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(78, 'Lisa Martinez', 'lisa.martinez@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(79, 'Matthew Hernandez', 'matthew.hernandez@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(80, 'Amanda Lopez', 'amanda.lopez@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(81, 'Christopher Gonzalez', 'christopher.gonzalez@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(82, 'Ashley Wilson', 'ashley.wilson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(83, 'Joshua Anderson', 'joshua.anderson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(84, 'Megan Thomas', 'megan.thomas@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(85, 'Andrew Taylor', 'andrew.taylor@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(86, 'Nicole Moore', 'nicole.moore@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(87, 'Kevin Jackson', 'kevin.jackson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(88, 'Stephanie Martin', 'stephanie.martin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(89, 'Brian Lee', 'brian.lee@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(90, 'Rebecca Perez', 'rebecca.perez@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(91, 'Timothy Thompson', 'timothy.thompson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(92, 'Laura White', 'laura.white@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(93, 'Richard Harris', 'richard.harris@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(94, 'Heather Sanchez', 'heather.sanchez@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(95, 'Jeffrey Clark', 'jeffrey.clark@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(96, 'Melissa Ramirez', 'melissa.ramirez@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(97, 'Ryan Lewis', 'ryan.lewis@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(98, 'Christina Robinson', 'christina.robinson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(99, 'Jonathan Walker', 'jonathan.walker@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(100, 'Rachel Young', 'rachel.young@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(101, 'Nathan Allen', 'nathan.allen@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(102, 'Amber King', 'amber.king@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(103, 'Samuel Scott', 'samuel.scott@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(104, 'Danielle Green', 'danielle.green@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(105, 'Brandon Baker', 'brandon.baker@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(106, 'Kayla Adams', 'kayla.adams@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(107, 'Justin Nelson', 'justin.nelson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(108, 'Samantha Hill', 'samantha.hill@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(109, 'Benjamin Rivera', 'benjamin.rivera@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(110, 'Hannah Mitchell', 'hannah.mitchell@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(111, 'Alexander Carter', 'alexander.carter@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(112, 'Victoria Roberts', 'victoria.roberts@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(113, 'Patrick Phillips', 'patrick.phillips@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(114, 'Brittany Campbell', 'brittany.campbell@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(115, 'Kyle Parker', 'kyle.parker@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(116, 'Courtney Evans', 'courtney.evans@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(117, 'Jacob Edwards', 'jacob.edwards@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(118, 'Alexis Collins', 'alexis.collins@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(119, 'Tyler Stewart', 'tyler.stewart@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(120, 'Jasmine Morris', 'jasmine.morris@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(121, 'Zachary Rogers', 'zachary.rogers@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(122, 'Allison Cook', 'allison.cook@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(123, 'Dylan Morgan', 'dylan.morgan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(124, 'Erica Bell', 'erica.bell@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(125, 'Cody Murphy', 'cody.murphy@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(126, 'Chelsea Bailey', 'chelsea.bailey@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(127, 'Austin Cooper', 'austin.cooper@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(128, 'Morgan Richardson', 'morgan.richardson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(129, 'Jared Cox', 'jared.cox@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(130, 'Kelsey Howard', 'kelsey.howard@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(131, 'Travis Ward', 'travis.ward@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(132, 'Lindsey Torres', 'lindsey.torres@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(133, 'Marcus Peterson', 'marcus.peterson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(134, 'Vanessa Gray', 'vanessa.gray@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(135, 'Corey Ramirez', 'corey.ramirez@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(136, 'Monica James', 'monica.james@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(137, 'Peter Watson', 'peter.watson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(138, 'Catherine Brooks', 'catherine.brooks@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(139, 'Ethan Kelly', 'ethan.kelly@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(140, 'Alexandra Sanders', 'alexandra.sanders@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(141, 'Joel Price', 'joel.price@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(142, 'Miranda Bennett', 'miranda.bennett@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(143, 'Derek Wood', 'derek.wood@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(144, 'Jacqueline Barnes', 'jacqueline.barnes@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(145, 'Sean Ross', 'sean.ross@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(146, 'Kristen Henderson', 'kristen.henderson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(147, 'Gregory Coleman', 'gregory.coleman@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(148, 'Lauren Jenkins', 'lauren.jenkins@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(149, 'Philip Perry', 'philip.perry@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(150, 'Maria Powell', 'maria.powell@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(151, 'Brett Long', 'brett.long@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(152, 'Tiffany Hughes', 'tiffany.hughes@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(153, 'Bradley Flores', 'bradley.flores@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(154, 'Katherine Washington', 'katherine.washington@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(155, 'Jeremy Butler', 'jeremy.butler@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(156, 'Crystal Simmons', 'crystal.simmons@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(157, 'Shawn Foster', 'shawn.foster@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(158, 'Brianna Gonzales', 'brianna.gonzales@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(159, 'Raymond Bryant', 'raymond.bryant@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(160, 'Evelyn Russell', 'evelyn.russell@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(161, 'Gabriel Griffin', 'gabriel.griffin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(162, 'Alexis Diaz', 'alexis.diaz@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(163, 'Logan Hayes', 'logan.hayes@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(164, 'Sabrina Myers', 'sabrina.myers@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(165, 'Carl Ford', 'carl.ford@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(166, 'Molly Hamilton', 'molly.hamilton@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(167, 'Dennis Graham', 'dennis.graham@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(168, 'Jocelyn Sullivan', 'jocelyn.sullivan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(169, 'Vincent Wallace', 'vincent.wallace@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(170, 'Angelica Woods', 'angelica.woods@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(171, 'Keith Cole', 'keith.cole@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(172, 'Kylie West', 'kylie.west@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(173, 'Alan Jordan', 'alan.jordan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(174, 'Erika Owens', 'erika.owens@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(175, 'Todd Reynolds', 'todd.reynolds@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(176, 'Whitney Fisher', 'whitney.fisher@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(177, 'Curtis Ellis', 'curtis.ellis@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(178, 'Tara Harrison', 'tara.harrison@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(179, 'Dale Gibson', 'dale.gibson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(180, 'Monique Mcdonald', 'monique.mcdonald@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(181, 'Leonard Cruz', 'leonard.cruz@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(182, 'Jill Marshall', 'jill.marshall@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(183, 'Eugene Ortiz', 'eugene.ortiz@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(184, 'Tanya Gomez', 'tanya.gomez@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(185, 'Barry Murray', 'barry.murray@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(186, 'Kara Freeman', 'kara.freeman@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(187, 'Harry Wells', 'harry.wells@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(188, 'Shelly Webb', 'shelly.webb@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(189, 'Wayne Simpson', 'wayne.simpson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(190, 'Misty Stevens', 'misty.stevens@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(191, 'Louis Tucker', 'louis.tucker@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(192, 'Latoya Porter', 'latoya.porter@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(193, 'Francis Hunter', 'francis.hunter@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(194, 'Toni Hicks', 'toni.hicks@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(195, 'Clarence Crawford', 'clarence.crawford@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(196, 'Loretta Henry', 'loretta.henry@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(197, 'Kirk Boyd', 'kirk.boyd@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(198, 'Lena Mason', 'lena.mason@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(199, 'Leroy Morales', 'leroy.morales@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(200, 'Lillian Kennedy', 'lillian.kennedy@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(201, 'Warren Warren', 'warren.warren@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(202, 'Dianne Dixon', 'dianne.dixon@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(203, 'Dean Ramos', 'dean.ramos@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(204, 'Gwendolyn Reyes', 'gwendolyn.reyes@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(205, 'Clifford Burns', 'clifford.burns@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(206, 'Vicki Gordon', 'vicki.gordon@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(207, 'Lance Shaw', 'lance.shaw@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(208, 'Kristina Holmes', 'kristina.holmes@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(209, 'Rodney Rice', 'rodney.rice@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(210, 'Yvonne Robertson', 'yvonne.robertson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(211, 'Tommy Hunt', 'tommy.hunt@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(212, 'Stacy Black', 'stacy.black@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(213, 'Johnny Daniels', 'johnny.daniels@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(214, 'Marlene Palmer', 'marlene.palmer@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(215, 'Neal Mills', 'neal.mills@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(216, 'Meredith Nichols', 'meredith.nichols@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(217, 'Dwayne Grant', 'dwayne.grant@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(218, 'Tricia Knight', 'tricia.knight@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(219, 'Kirk Ferguson', 'kirk.ferguson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(220, 'Lynne Rose', 'lynne.rose@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(221, 'Lester Stone', 'lester.stone@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(222, 'Priscilla Hawkins', 'priscilla.hawkins@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(223, 'Darryl Dunn', 'darryl.dunn@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(224, 'Joyce Perkins', 'joyce.perkins@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(225, 'Kent Hudson', 'kent.hudson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(226, 'Bobbie Spencer', 'bobbie.spencer@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(227, 'Clyde Gardner', 'clyde.gardner@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(228, 'Gayle Stephens', 'gayle.stephens@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(229, 'Roland Payne', 'roland.payne@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(230, 'Gretchen Pierce', 'gretchen.pierce@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(231, 'Lyle Berry', 'lyle.berry@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(232, 'Daisy Matthews', 'daisy.matthews@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(233, 'Lionel Arnold', 'lionel.arnold@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(234, 'Paulette Wagner', 'paulette.wagner@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(235, 'Alton Willis', 'alton.willis@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(236, 'Lola Ray', 'lola.ray@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(237, 'Rex Watkins', 'rex.watkins@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(238, 'Lydia Olson', 'lydia.olson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(239, 'Drew Carroll', 'drew.carroll@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(240, 'Lena Duncan', 'lena.duncan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(241, 'Rudy Snyder', 'rudy.snyder@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(242, 'Darla Hart', 'darla.hart@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(243, 'Lonnie Cunningham', 'lonnie.cunningham@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(244, 'Kara Bradley', 'kara.bradley@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(245, 'Daryl Lane', 'daryl.lane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(246, 'Lorene Andrews', 'lorene.andrews@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(247, 'Lorenzo Ruiz', 'lorenzo.ruiz@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(248, 'Dianna Harper', 'dianna.harper@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(249, 'Lyle Fox', 'lyle.fox@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(250, 'Lola Riley', 'lola.riley@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(251, 'Lionel Armstrong', 'lionel.armstrong@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(252, 'Paulette Carpenter', 'paulette.carpenter@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(253, 'Alton Weaver', 'alton.weaver@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(254, 'Lola Greene', 'lola.greene@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(255, 'Rex George', 'rex.george@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(256, 'Lydia Marshall', 'lydia.marshall@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(257, 'Drew Simpson', 'drew.simpson@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(258, 'Lena Holmes', 'lena.holmes@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(259, 'Rudy Washington', 'rudy.washington@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(260, 'Darla Butler', 'darla.butler@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(261, 'Lonnie Simmons', 'lonnie.simmons@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(262, 'Kara Foster', 'kara.foster@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(263, 'Daryl Gonzales', 'daryl.gonzales@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(264, 'Lorene Bryant', 'lorene.bryant@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(265, 'Lorenzo Russell', 'lorenzo.russell@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(266, 'Dianna Griffin', 'dianna.griffin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(267, 'Lyle Diaz', 'lyle.diaz@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(268, 'Lola Hayes', 'lola.hayes@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(269, 'Lionel Myers', 'lionel.myers@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(270, 'Paulette Ford', 'paulette.ford@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(271, 'Alton Hamilton', 'alton.hamilton@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(272, 'Lola Graham', 'lola.graham@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27'),
(273, 'Rex Sullivan', 'rex.sullivan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USA', '2025-03-26 14:32:27');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `offer_responses`
--
ALTER TABLE `offer_responses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_offer_user` (`offer_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_country` (`country`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `offer_responses`
--
ALTER TABLE `offer_responses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `offer_responses`
--
ALTER TABLE `offer_responses`
  ADD CONSTRAINT `offer_responses_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offer_responses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
