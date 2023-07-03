
-- --------------------------------------------------------

--
-- Structure de la table `cc_boutiques_gerants`
--

DROP TABLE IF EXISTS `cc_boutiques_gerants`;
CREATE TABLE `cc_boutiques_gerants` (
  `persoid` int(12) NOT NULL DEFAULT '0',
  `boutiqueid` mediumint(8) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_boutiques_gerants`
--

INSERT INTO `cc_boutiques_gerants` (`persoid`, `boutiqueid`) VALUES
(346, 566),
(346, 1142),
(347, 149),
(347, 340),
(358, 563),
(367, 143),
(390, 593),
(390, 606),
(414, 143),
(414, 1140),
(414, 1155),
(479, 141),
(479, 182),
(769, 612),
(793, 581),
(834, 1133),
(834, 1148),
(834, 1169),
(889, 141),
(1062, 148),
(1110, 1130),
(1116, 332),
(1120, 143),
(1122, 646),
(1149, 1181),
(1240, 569),
(1243, 141),
(1243, 1128),
(1273, 141),
(1273, 1112),
(1273, 1127),
(1275, 1140),
(1275, 1182),
(1289, 1134),
(1316, 1130),
(1325, 1136),
(1336, 1133),
(1448, 1136),
(1457, 1181),
(1468, 1180),
(1520, 141),
(1539, 1174),
(1564, 1180),
(1613, 1171),
(1634, 141),
(1651, 1211),
(1670, 1181),
(1686, 1181),
(1787, 1151),
(1868, 1171);
