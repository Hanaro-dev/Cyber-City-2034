
-- --------------------------------------------------------

--
-- Structure de la table `cc_lieu_ban`
--

DROP TABLE IF EXISTS `cc_lieu_ban`;
CREATE TABLE `cc_lieu_ban` (
  `id` int(12) UNSIGNED NOT NULL,
  `persoid` int(5) UNSIGNED NOT NULL DEFAULT '0',
  `lieu` varchar(150) NOT NULL,
  `remiseleft` smallint(1) NOT NULL DEFAULT '9'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
