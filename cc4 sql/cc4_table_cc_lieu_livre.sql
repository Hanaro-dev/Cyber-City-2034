
-- --------------------------------------------------------

--
-- Structure de la table `cc_lieu_livre`
--

DROP TABLE IF EXISTS `cc_lieu_livre`;
CREATE TABLE `cc_lieu_livre` (
  `lieuId` mediumint(8) UNSIGNED NOT NULL,
  `itemDbId` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_lieu_livre`
--

INSERT INTO `cc_lieu_livre` (`lieuId`, `itemDbId`) VALUES
(164, 779),
(189, 316),
(189, 317),
(189, 320),
(189, 330),
(189, 333),
(189, 788),
(189, 1144),
(190, 1144),
(191, 1144),
(193, 1144),
(196, 1144),
(252, 578),
(318, 645),
(397, 426),
(397, 427),
(397, 476),
(397, 755),
(397, 1104),
(397, 1144),
(566, 880),
(601, 1144),
(1133, 1187),
(1133, 1372),
(1133, 1373),
(1133, 1374),
(1133, 1383),
(1133, 1384),
(1180, 1188);
