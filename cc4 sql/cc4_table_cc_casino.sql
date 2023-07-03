
-- --------------------------------------------------------

--
-- Structure de la table `cc_casino`
--

DROP TABLE IF EXISTS `cc_casino`;
CREATE TABLE `cc_casino` (
  `casino_id` int(12) NOT NULL,
  `casino_lieu` varchar(100) NOT NULL,
  `casino_nom` varchar(50) NOT NULL,
  `casino_cash` int(12) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_casino`
--

INSERT INTO `cc_casino` (`casino_id`, `casino_lieu`, `casino_nom`, `casino_cash`) VALUES
(1, 'Test Eclipse', 'casino', 7000);
