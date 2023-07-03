
-- --------------------------------------------------------

--
-- Structure de la table `cc_mj`
--

DROP TABLE IF EXISTS `cc_mj`;
CREATE TABLE `cc_mj` (
  `id` int(12) NOT NULL,
  `userId` int(12) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `poste` varchar(100) NOT NULL,
  `email_prefix` varchar(20) NOT NULL,
  `present` smallint(1) NOT NULL DEFAULT '0',
  `ax_ppa` smallint(1) NOT NULL DEFAULT '0',
  `ax_ej` smallint(1) NOT NULL DEFAULT '0',
  `ax_hj` smallint(1) NOT NULL DEFAULT '0',
  `ax_admin` smallint(1) NOT NULL DEFAULT '0',
  `last_connection` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ax_dev` smallint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_mj`
--

INSERT INTO `cc_mj` (`id`, `userId`, `nom`, `poste`, `email_prefix`, `present`, `ax_ppa`, `ax_ej`, `ax_hj`, `ax_admin`, `last_connection`, `ax_dev`) VALUES
(44, 1510, 'Eldrim', 'MJ Tech', '', 0, 1, 1, 1, 0, '2010-04-02 15:28:22', 1),
(47, 1530, 'Leuk', 'Administrateur', '', 1, 1, 1, 1, 1, '2010-04-02 15:28:22', 1),
(48, 325, 'Maëlstrom', 'Consultant', '', 0, 0, 1, 0, 1, '2010-04-02 15:28:22', 1),
(51, 1919, 'Opus', 'MJ (réserviste)', '', 0, 1, 1, 1, 0, '2010-04-02 15:28:22', 0),
(59, 2347, 'Cindy', 'Développeuse', '', 1, 1, 1, 1, 1, '2010-04-02 15:28:22', 1);
