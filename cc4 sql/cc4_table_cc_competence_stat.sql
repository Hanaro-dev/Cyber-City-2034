
-- --------------------------------------------------------

--
-- Structure de la table `cc_competence_stat`
--

DROP TABLE IF EXISTS `cc_competence_stat`;
CREATE TABLE `cc_competence_stat` (
  `compid` smallint(2) UNSIGNED NOT NULL,
  `statid` smallint(2) UNSIGNED NOT NULL,
  `stat_multi` tinyint(1) NOT NULL COMMENT 'multiplicateur, utile pour faire ARMB = 1xint+2xagi'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_competence_stat`
--

INSERT INTO `cc_competence_stat` (`compid`, `statid`, `stat_multi`) VALUES
(2, 1, 2),
(2, 3, 1),
(3, 1, 2),
(3, 2, 1),
(3, 3, 1),
(4, 2, 1),
(4, 5, 2),
(5, 2, 1),
(5, 3, 1),
(5, 5, 2),
(6, 2, 1),
(6, 4, 1),
(7, 2, 1),
(7, 5, 1),
(8, 1, 1),
(8, 3, 1),
(9, 4, 1),
(10, 2, 1),
(10, 4, 2),
(11, 2, 1),
(11, 5, 1),
(12, 4, 1),
(14, 2, 1),
(14, 4, 2),
(16, 2, 1),
(16, 4, 1),
(17, 4, 1),
(18, 1, 1),
(18, 5, 1),
(19, 2, 1),
(19, 4, 1),
(20, 2, 1),
(20, 3, 1),
(20, 4, 1),
(21, 1, 2),
(21, 5, 1),
(22, 4, 1),
(23, 4, 1),
(24, 2, 1),
(24, 4, 2),
(25, 1, 1),
(25, 3, 1),
(25, 5, 1),
(26, 2, 1),
(26, 4, 1),
(28, 2, 1),
(28, 5, 1),
(29, 1, 1),
(29, 5, 1),
(31, 4, 1),
(32, 4, 1);
