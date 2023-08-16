-- Adminer 4.7.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE DATABASE `projet03_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `projet03_db`;

DROP TABLE IF EXISTS `acteur`;
CREATE TABLE `acteur` (
  `id_acteur` int(11) NOT NULL AUTO_INCREMENT,
  `acteur` varchar(64) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `logo` varchar(64) NOT NULL,
  PRIMARY KEY (`id_acteur`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

INSERT INTO `acteur` (`id_acteur`, `acteur`, `description`, `logo`) VALUES
(1,	'Formation&co',	'Formation&co est une association française présente sur tout le territoire.\r\nNous proposons à des personnes issues de tout milieu de devenir entrepreneur grâce à un crédit et un accompagnement professionnel et personnalisé.\r\nNotre proposition : \r\n- un financement jusqu’à 30 000€ ;\r\n- un suivi personnalisé et gratuit ;\r\n- une lutte acharnée contre les freins sociétaux et les stéréotypes.\r\n\r\nLe financement est possible, peu importe le métier : coiffeur, banquier, éleveur de chèvres… . Nous collaborons avec des personnes talentueuses et motivées.\r\nVous n’avez pas de diplômes ? Ce n’est pas un problème pour nous ! Nos financements s’adressent à tous.',	'02.png'),
(2,	'Protectpeople',	'Protectpeople finance la solidarité nationale.\r\nNous appliquons le principe édifié par la Sécurité sociale française en 1945 : permettre à chacun de bénéficier d’une protection sociale.\r\n\r\nChez Protectpeople, chacun cotise selon ses moyens et reçoit selon ses besoins.\r\nProectecpeople est ouvert à tous, sans considération d’âge ou d’état de santé.\r\nNous garantissons un accès aux soins et une retraite.\r\nChaque année, nous collectons et répartissons 300 milliards d’euros.\r\nNotre mission est double :\r\nsociale : nous garantissons la fiabilité des données sociales ;\r\néconomique : nous apportons une contribution aux activités économiques.',	'03.png'),
(3,	'Dsa France',	'Dsa France accélère la croissance du territoire et s’engage avec les collectivités territoriales.\r\nNous accompagnons les entreprises dans les étapes clés de leur évolution.\r\nNotre philosophie : s’adapter à chaque entreprise.\r\nNous les accompagnons pour voir plus grand et plus loin et proposons des solutions de financement adaptées à chaque étape de la vie des entreprises.',	'01.png'),
(4,	'CDE',	'La CDE (Chambre Des Entrepreneurs) accompagne les entreprises dans leurs démarches de formation. \r\nSon président est élu pour 3 ans par ses pairs, chefs d’entreprises et présidents des CDE.',	'00.png');

DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `id_post` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_acteur` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  `post` varchar(512) NOT NULL,
  PRIMARY KEY (`id_post`),
  KEY `id_user` (`id_user`),
  KEY `id_acteur` (`id_acteur`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_ibfk_2` FOREIGN KEY (`id_acteur`) REFERENCES `acteur` (`id_acteur`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(64) NOT NULL,
  `prenom` varchar(64) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `question` varchar(512) NOT NULL,
  `reponse` varchar(512) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id_user`, `nom`, `prenom`, `username`, `password`, `question`, `reponse`) VALUES
(52,	'LOURDAUX',	'Florian',	'flourdau',	'$2y$10$yvG.atZKfCSgBmAWuyR0nOYAU0MsFuLYItPUj9GF1Iw2MDVqGTkSW',	'Lieu de naissance',	'Cambrai dans le 59 alert(\'coucou\');'),
(59,	'',	'',	'flourdauTEST',	'$2y$10$yvG.atZKfCSgBmAWuyR0nOYAU0MsFuLYItPUj9GF1Iw2MDVqGTkSW',	'',	''),
(60,	'',	'',	'kfouziya',	'$2y$10$yvG.atZKfCSgBmAWuyR0nOYAU0MsFuLYItPUj9GF1Iw2MDVqGTkSW',	'',	'');

DROP TABLE IF EXISTS `vote`;
CREATE TABLE `vote` (
  `id_user` int(11) NOT NULL,
  `id_acteur` int(11) NOT NULL,
  `vote` binary(1) NOT NULL,
  KEY `id_acteur` (`id_acteur`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`id_acteur`) REFERENCES `acteur` (`id_acteur`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vote_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `vote` (`id_user`, `id_acteur`, `vote`) VALUES
(52,	1,	UNHEX('30'));

-- 2020-01-19 10:06:11
