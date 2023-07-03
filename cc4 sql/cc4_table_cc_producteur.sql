
-- --------------------------------------------------------

--
-- Structure de la table `cc_producteur`
--

DROP TABLE IF EXISTS `cc_producteur`;
CREATE TABLE `cc_producteur` (
  `id` smallint(2) UNSIGNED NOT NULL,
  `lieuId` mediumint(8) UNSIGNED NOT NULL COMMENT 'ID du lieu où est attaché le module de production',
  `cash` int(11) NOT NULL,
  `pa_cash_ratio` float NOT NULL COMMENT '1pa donne Xcash',
  `total_pa` int(12) NOT NULL,
  `pa_needed` int(12) NOT NULL COMMENT 'pt requis pour lancer une production',
  `comp_requise` varchar(4) DEFAULT NULL,
  `comp_lvl` int(2) DEFAULT NULL,
  `comp_xp` int(4) DEFAULT NULL,
  `lieuMenuId` int(12) NOT NULL DEFAULT '0' COMMENT 'Nom du module de production'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_producteur`
--

INSERT INTO `cc_producteur` (`id`, `lieuId`, `cash`, `pa_cash_ratio`, `total_pa`, `pa_needed`, `comp_requise`, `comp_lvl`, `comp_xp`, `lieuMenuId`) VALUES
(5, 196, 1376669, 30, 60000, 60000, '', 0, 0, 84),
(6, 601, 88, 30, 22060, 60000, '', 0, 0, 108),
(8, 1151, 19279, 2, 20, 110, 'info', 10, 3, 621),
(10, 1135, 0, 0, 200, 200, 'forg', 0, 2, 623),
(20, 1135, 0, 0, 120, 120, 'forg', 3, 2, 653),
(21, 1135, 0, 0, 0, 80, 'forg', 6, 2, 654),
(22, 1126, 0, 0, 200, 200, 'chim', 0, 2, 624),
(23, 1126, 0, 0, 10, 120, 'chim', 3, 2, 651),
(25, 1131, 0, 0, 200, 200, 'armu', 0, 2, 627),
(26, 1131, 0, 0, 120, 120, 'armu', 3, 2, 655),
(27, 1131, 0, 0, 80, 80, 'armu', 6, 2, 656),
(28, 1172, 0, 0, 0, 200, 'agro', 0, 2, 631),
(29, 1172, 0, 0, 30, 120, 'agro', 3, 2, 660),
(30, 1172, 0, 0, 50, 80, 'agro', 6, 2, 661),
(31, 1127, 0, 0, 50, 50, 'meds', 0, 2, 626),
(32, 1127, 0, 0, 0, 30, 'meds', 3, 2, 658),
(33, 1127, 0, 0, 0, 20, 'meds', 6, 2, 659),
(34, 1128, 0, 0, 20, 150, 'cybr', 0, 2, 625),
(35, 1128, 0, 0, 90, 90, 'cybr', 3, 2, 649),
(36, 1128, 0, 0, 60, 60, 'cybr', 6, 2, 650),
(38, 1151, 0, 0, 80, 100, 'agro', 6, 5, 648),
(40, 1126, 0, 0, 50, 80, 'chim', 6, 2, 652),
(41, 1126, 0, 0, 0, 120, 'chim', 5, 2, 700);
