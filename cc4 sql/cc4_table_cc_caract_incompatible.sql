
-- --------------------------------------------------------

--
-- Structure de la table `cc_caract_incompatible`
--

DROP TABLE IF EXISTS `cc_caract_incompatible`;
CREATE TABLE `cc_caract_incompatible` (
  `id1` smallint(2) UNSIGNED NOT NULL,
  `id2` smallint(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_caract_incompatible`
--

INSERT INTO `cc_caract_incompatible` (`id1`, `id2`) VALUES
(2, 13),
(3, 2),
(3, 13),
(4, 5),
(4, 12),
(7, 6),
(9, 10),
(9, 13),
(15, 16),
(32, 33),
(34, 35),
(41, 43),
(42, 43),
(45, 46),
(47, 46),
(48, 49),
(74, 33),
(86, 60);
