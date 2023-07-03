
-- --------------------------------------------------------

--
-- Structure de la table `cc_item_db_armemunition`
--

DROP TABLE IF EXISTS `cc_item_db_armemunition`;
CREATE TABLE `cc_item_db_armemunition` (
  `id` int(8) NOT NULL,
  `db_armeid` int(8) NOT NULL DEFAULT '0',
  `db_munitionid` int(8) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_item_db_armemunition`
--

INSERT INTO `cc_item_db_armemunition` (`id`, `db_armeid`, `db_munitionid`) VALUES
(9, 50, 58),
(8, 51, 57),
(124, 51, 108),
(7, 53, 56),
(122, 121, 108),
(19, 122, 107),
(21, 124, 104),
(25, 128, 108),
(34, 137, 57),
(105, 138, 105),
(123, 140, 56),
(38, 141, 56),
(56, 159, 58),
(58, 161, 108),
(71, 683, 104),
(107, 726, 56),
(79, 735, 57),
(109, 737, 57),
(110, 850, 108),
(111, 1060, 1181),
(101, 1119, 112),
(104, 1141, 1142),
(112, 1258, 107),
(121, 1287, 56),
(133, 1287, 101),
(114, 1287, 103),
(116, 1287, 104),
(113, 1287, 105),
(115, 1287, 106),
(117, 1287, 107),
(118, 1287, 112),
(120, 1287, 1181),
(126, 1287, 1364),
(127, 1365, 1364),
(128, 1381, 56),
(132, 1412, 101),
(131, 1422, 101);
