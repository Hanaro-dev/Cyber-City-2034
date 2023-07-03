
-- --------------------------------------------------------

--
-- Structure de la table `cc_lieu_distributeur`
--

DROP TABLE IF EXISTS `cc_lieu_distributeur`;
CREATE TABLE `cc_lieu_distributeur` (
  `id` int(12) UNSIGNED NOT NULL,
  `lieuId` mediumint(8) UNSIGNED NOT NULL,
  `producteurId` smallint(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_lieu_distributeur`
--

INSERT INTO `cc_lieu_distributeur` (`id`, `lieuId`, `producteurId`) VALUES
(8, 141, 6),
(5, 196, 5),
(6, 592, 5),
(7, 592, 6);
