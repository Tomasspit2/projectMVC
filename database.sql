-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 26 Octobre 2017 à 13:53
-- Version du serveur :  5.7.19-0ubuntu0.16.04.1
-- Version de PHP :  7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `simple-mvc`
--

-- --------------------------------------------------------

--
-- Structure de la table `item`
--
create database auto_encheres;
USE auto_encheres;

create table item
(
    id    int unsigned auto_increment
        primary key,
    title varchar(255) not null
)
    charset = latin1;

create table encheres
(
    id             bigint unsigned auto_increment
        primary key,
    date           bigint unsigned not null,
    montant        float unsigned  not null
)
    collate = utf8mb4_unicode_ci;

create table utilisateurs
(
    id       bigint unsigned auto_increment
        primary key,
    nom      varchar(25) not null,
    prenom   varchar(25) not null,
    email    varchar(35) not null,
    password varchar(64) not null,
    id_encheres bigint unsigned null,
        constraint fk_encheres_utilisateur
        foreign key (id_encheres) references encheres (id)
        on update cascade on delete cascade
)
    collate = utf8mb4_unicode_ci;



create table annonces
(
    id               bigint unsigned auto_increment
        primary key,
    titre_annonce    varchar(1000)     not null,
    prix_depart      float unsigned    not null,
    date_fin_enchere bigint unsigned   not null,
    modele           varchar(255)      not null,
    marque           varchar(255)      not null,
    puissance        smallint unsigned not null,
    annee            smallint unsigned not null,
    description      text              not null,
    photo            varchar(1000)     null,
    id_enchere bigint unsigned null,
    constraint fk_annonce_enchere
        foreign key (id_enchere) references encheres (id)
            on update cascade on delete cascade
)
    collate = utf8mb4_unicode_ci;

INSERT INTO `annonces` (`id`, `titre_annonce`, `prix_depart`, `date_fin_enchere`, `modele`, `marque`, `puissance`, `annee`, `description`, `photo`) VALUES
                                                                                                                                                        (1, 'La BM de sa mère !', 92000, 1693479600, 'M3', 'BMW', 230, 2019, 'Cette BMW M3 déchire vraiment la race de sa mère.', 'bmw.jpg'),
                                                                                                                                                        (2, 'L\'Audi qui déchire sa race !', 122000, 1693479600, 'A3 RS6 Quattro Sportback', 'AUDI', 320, 2021, 'L\'AUDI de la mort qui tue. Achète mon fils !', 'audi.jpg'),
                                                                                                                                                        (3, 'Ma MercoBenz Zarma !', 94000, 1693479600, 'ML 300', 'MERCEDES', 280, 2020, 'Laisse moi ZoomZoomZing, dans ta Benz Benz Benz.', 'mercedes.jpg'),
                                                                                                                                                        (4, 'Bientôt ta Porsche veinard ?', 132000, 1693479600, 'TAYCAN GT3', 'PORSCHE', 230, 2022, 'La porsche de mes rêves, bordel !', 'porsche.jpg'),
                                                                                                                                                        (5, 'Fait chauffer Enzo !', 154000, 1693479600, '812 GTS', 'FERRARI', 430, 2019, 'Cette Ferrari n\'est pas rouge. Sacrilège !', 'ferrari.jpg'),
                                                                                                                                                        (6, 'Tu veux jouer avec la nouvelle Golf ?', 67000, 1693479600, 'Golf 8 Spider', 'VOLKSWAGEN', 190, 2019, 'Cette golf est une valeur sûre.', 'volkswagen.jpg'),
                                                                                                                                                        (7, 'C\'est toi le MAC ?', 145000, 1693479600, '570 GT', 'MC LAREN', 360, 2022, 'There is No Finish Line. There are no limit !', 'mclaren.jpg'),
                                                                                                                                                        (8, 'Ça balance du cheval grave !', 134000, 1693479600, 'Camaro', 'CHEVROLET', 560, 2022, 'Ça c\'est une voiture qu\'elle a des chevaux sous le capot !', 'chevrolet.jpg'),
                                                                                                                                                        (9, 'Drive your Ambition with a Mitsubishi', 76000, 1693479600, 'Lancer Evolution 6', 'MITSUBISHI', 180, 2020, 'La caisse là, elle mange la route grave ! ', 'mitsubishi.jpg'),
                                                                                                                                                        (10, 'Tu veux un moteur d\'avion sous le capot ?', 199000, 1693479600, 'Ghost', 'ROLLS ROYCE', 571, 2020, 'Elle pèse 2,5 tonnes la bête !', 'rollsroyce.jpg'),
                                                                                                                                                        (11, 'Alpine, en un seul mot !', 119000, 1693479600, 'A110', 'ALPINE', 280, 2022, 'Cocorico, Alpine est la seule marque française qui rivalise avec les voitures étrangères.', 'alpine.jpg'),
                                                                                                                                                        (12, 'We are Not Super Cars. We are Lamborghini !', 165000, 1693479600, 'Aventador LP 780-4 Ultimae Roadster', 'LAMBORGHINI', 480, 2022, 'Achète ça et tu perds tes deux bras...', 'lamborghini.jpg'),
                                                                                                                                                        (13, 'Tu veux frimer ?', 99000, 1693479600, 'RS8 Coupé Sport', 'AUDI', 280, 2021, 'Une AUDI sinon rien...', 'audi2.jpg'),
                                                                                                                                                        (14, 'Allie sensations de conduite et confort.', 95000, 1693479600, 'Corvette Grand Sport', 'CHEVROLET', 650, 2017, 'Ce nouveau modèle Grand Sport, c\'est bien la version \"puristes\".', 'chevrolet2.jpg'),
                                                                                                                                                        (15, 'Un SUV 100% électrique.', 176000, 1693479600, 'Purosangue', 'FERRARI', 454, 2022, 'Le premier SUV Ferrari.', 'ferrari2.jpg');


INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `password`) VALUES
                                                                            (1, 'DOE', 'John', 'john.doe@gmail.com', 'f94f09d6d7c1e4f151c0232cad774f0e'),
                                                                            (2, 'Biden', 'Joe', 'president@fuckland.com', 'e10adc3949ba59abbe56e057f20f883e');


INSERT INTO `encheres` (`id`, `date`, `montant`) VALUES
    (1, '1693479600', '25000');
-- Supprimez la clé étrangère existante dans la table `utilisateurs`
ALTER TABLE utilisateurs
    DROP FOREIGN KEY fk_encheres_utilisateur;

-- Supprimez la colonne `id_encheres` de la table `utilisateurs`
ALTER TABLE utilisateurs
    DROP COLUMN id_encheres;

-- Créez une nouvelle table de jointure `utilisateurs_encheres`
CREATE TABLE utilisateurs_encheres (
                                       id_utilisateur bigint unsigned,
                                       id_enchere bigint unsigned,
                                       PRIMARY KEY (id_utilisateur, id_enchere),
                                       FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id),
                                       FOREIGN KEY (id_enchere) REFERENCES encheres (id)
);

