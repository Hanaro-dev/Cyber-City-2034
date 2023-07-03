
-- --------------------------------------------------------

--
-- Structure de la table `cc_perso_fouille`
--

DROP TABLE IF EXISTS `cc_perso_fouille`;
CREATE TABLE `cc_perso_fouille` (
  `fromid` int(5) UNSIGNED NOT NULL COMMENT 'fouille par',
  `toid` int(5) UNSIGNED NOT NULL COMMENT 'sera fouillé',
  `expiration` int(10) NOT NULL,
  `reponse` smallint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_perso_fouille`
--

INSERT INTO `cc_perso_fouille` (`fromid`, `toid`, `expiration`, `reponse`) VALUES
(976, 1433, 1373352066, 0),
(1149, 1666, 1403714937, 0),
(1149, 1885, 1434821470, 1),
(1450, 1505, 1390151565, 0),
(1520, 1625, 1400961872, 0),
(1520, 1764, 1415860697, 0),
(1686, 1949, 1450648304, 1);
