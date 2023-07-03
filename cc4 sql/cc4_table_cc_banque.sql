
-- --------------------------------------------------------

--
-- Structure de la table `cc_banque`
--

DROP TABLE IF EXISTS `cc_banque`;
CREATE TABLE `cc_banque` (
  `banque_id` int(12) NOT NULL,
  `banque_lieu` varchar(100) NOT NULL,
  `banque_no` int(4) NOT NULL DEFAULT '0',
  `banque_nom` varchar(50) NOT NULL,
  `banque_retrait` smallint(1) NOT NULL DEFAULT '1',
  `banque_frais_ouverture` int(8) NOT NULL DEFAULT '0',
  `banque_telephone` smallint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_banque`
--

INSERT INTO `cc_banque` (`banque_id`, `banque_lieu`, `banque_no`, `banque_nom`, `banque_retrait`, `banque_frais_ouverture`, `banque_telephone`) VALUES
(6, 'Sud.baranoob', 3727, 'DomTel', 0, 2000, 1),
(7, 'Est.bank', 5878, 'Banque Citoyenne', 1, 1000, 0),
(8, 'CachetteEldrim', 3948, 'La Banque', 1, 0, 1);
