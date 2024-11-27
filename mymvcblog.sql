-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 27 nov. 2024 à 08:12
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mymvcblog`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` char(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`) VALUES
(1, 'test@test.com', '$2y$14$kefF6aqkuOEWo7CIFduNf.7O8BuGR4uWrIAFcHWm2u99OcLPDFWOe'),
(2, 'test@test.com', '$2y$14$kefF6aqkuOEWo7CIFduNf.7O8BuGR4uWrIAFcHWm2u99OcLPDFWOe');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `comment`, `created_at`, `status`) VALUES
(2, 19, 1, 'test', '2024-10-17 10:20:44', 'approved'),
(6, 8, 2, 'Flutter j\'aime pas.', '2024-10-18 12:11:56', 'pending');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` longtext NOT NULL,
  `createdDate` datetime NOT NULL DEFAULT current_timestamp(),
  `preview` text DEFAULT NULL,
  `updatedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `title`, `body`, `createdDate`, `preview`, `updatedDate`) VALUES
(7, 'testtest', 'testzetzetzetztzetd', '2024-10-10 10:42:20', 'd', '2024-10-10 12:20:37'),
(8, 'Pourquoi Flutter est devenu un choix populaire pour le développement multi-plateformes', 'Flutter, le framework de développement mobile de Google, gagne toujours plus rapidement en popularité pour le développement d\'applications multi-plateformes. Grâce à sa capacité à compiler en code natif pour Android et iOS, Flutter permet une expérience utilisateur fluide et cohérente sur les deux plateformes. Découvrez pourquoi les développeurs choisissent Flutter pour leurs projets mobiles en 2024.\n\nFlutter utilise le langage Dart, qui est simple à apprendre et puissant. Les développeurs apprécient la rapidité du framework et ses outils de débogage robustes. La communauté Flutter est en pleine croissance, offrant une multitude de plugins et de packages qui simplifient le développement.\n\nL\'un des points forts de Flutter est son rendu graphique performant grâce au moteur Skia, qui permet des animations fluides et des interfaces utilisateur réactives. De plus, la fonctionnalité de Hot Reload accélère le cycle de développement en permettant aux développeurs de voir instantanément les modifications apportées au code.\n\nFlutter n\'est pas seulement limité aux applications mobiles; il permet également le développement pour le web et le bureau, ce qui en fait un véritable framework multi-plateformes. Cette flexibilité, combinée à une forte adoption par des entreprises comme Alibaba et Google, renforce sa position comme un outil incontournable pour les développeurs en 2024.', '2024-10-10 10:55:27', 'Flutter, le framework de développement mobile de Google, gagne toujours plus rapidement en popularité.', '2024-10-10 11:21:41'),
(9, 'Introduction à l\'apprentissage profond et ses applications', 'L\'apprentissage profond, une branche de l\'intelligence artificielle, révolutionne divers secteurs, allant de la reconnaissance vocale à la détection de maladies. Cet article fournit une introduction accessible à l\'apprentissage profond, expliquant ses principes de base et comment il est utilisé pour résoudre des problèmes complexes dans la vie réelle.\r\n\r\nL\'apprentissage profond utilise des réseaux de neurones artificiels inspirés du cerveau humain pour analyser et interpréter des données complexes. Ces réseaux, composés de couches de neurones interconnectés, peuvent apprendre à partir de grandes quantités de données et améliorer leurs performances au fil du temps.\r\n\r\nParmi les applications les plus impressionnantes de l\'apprentissage profond, on trouve la vision par ordinateur, qui permet aux machines de reconnaître des objets et des visages dans des images et des vidéos. La reconnaissance vocale, utilisée par des assistants virtuels comme Siri et Alexa, repose également sur ces technologies pour comprendre et répondre aux commandes vocales des utilisateurs.\r\n\r\nEn médecine, l\'apprentissage profond aide à diagnostiquer des maladies en analysant des images médicales comme des radiographies et des IRM. Il est également utilisé pour prédire des résultats cliniques et personnaliser les traitements pour les patients. D\'autres domaines, comme les véhicules autonomes et les systèmes de recommandation (Netflix, Amazon), bénéficient également de l\'apprentissage profond pour offrir des expériences utilisateur améliorées et plus sûres.', '2024-10-10 11:11:14', 'L\'apprentissage profond, une branche de l\'intelligence artificielle, révolutionne divers secteurs.', '2024-11-24 19:15:48'),
(19, 'test', 'test', '2024-10-10 12:04:51', 'test', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `post_tags`
--

CREATE TABLE `post_tags` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `post_tags`
--

INSERT INTO `post_tags` (`post_id`, `tag_id`) VALUES
(7, 4),
(8, 5),
(8, 6),
(9, 7),
(19, 7);

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'PHP'),
(2, 'JS'),
(3, 'SQL'),
(4, 'HTML'),
(5, 'Mobile'),
(6, 'Flutter'),
(7, 'IA');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password` char(60) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'test', 'test@gmail.com', '$2y$10$Z7Mr3ziJ5LF8.sGAuMpu2uNqe7iVZPEPWSphvfQ.rp/NKZY9y55ZO', 'user'),
(2, 'tcardo06', 'tcardo06@gmail.com', '$2y$10$ut0Bla/xEfGbmm5vcuRFYeP.pAzAgA48Wf62ceDem0oh/Je6hyvUu', 'admin'),
(3, 'Jane', 'jane@gmail.com', '$2y$10$2FqgpLNox0JNWTBVdEs7Puw7BhK03Jyj/mSw2c9ZDqvmsw8uazxcS', 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `post_tags`
--
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `post_tags`
--
ALTER TABLE `post_tags`
  ADD CONSTRAINT `post_tags_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
