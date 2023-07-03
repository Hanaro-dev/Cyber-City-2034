
-- --------------------------------------------------------

--
-- Structure de la table `cc_lieu_tenirporte`
--

DROP TABLE IF EXISTS `cc_lieu_tenirporte`;
CREATE TABLE `cc_lieu_tenirporte` (
  `id` int(12) UNSIGNED NOT NULL,
  `de` varchar(150) NOT NULL,
  `vers` varchar(150) NOT NULL,
  `qui` int(5) UNSIGNED NOT NULL,
  `expiration` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
