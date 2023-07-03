
-- --------------------------------------------------------

--
-- Structure de la table `cc_sitesweb_acces`
--

DROP TABLE IF EXISTS `cc_sitesweb_acces`;
CREATE TABLE `cc_sitesweb_acces` (
  `id` int(12) NOT NULL,
  `site_id` int(12) NOT NULL,
  `user` varchar(20) NOT NULL,
  `pass` varchar(20) NOT NULL,
  `accede` enum('0','1') NOT NULL DEFAULT '0',
  `poste` enum('0','1') NOT NULL DEFAULT '0',
  `modifier` enum('0','1') NOT NULL DEFAULT '0',
  `admin` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_sitesweb_acces`
--

INSERT INTO `cc_sitesweb_acces` (`id`, `site_id`, `user`, `pass`, `accede`, `poste`, `modifier`, `admin`) VALUES
(1, 2, 'jacky', '6666', '1', '1', '1', '1'),
(2, 3, 'Ebola', 'test', '1', '1', '1', '1'),
(4, 2, 'jean', '6667', '1', '1', '1', '0'),
(12, 2, '', '', '0', '0', '0', '0'),
(13, 4, 'Ebola', '8789', '1', '1', '1', '1'),
(14, 5, 'Lorda', 'Lorda', '1', '1', '1', '1'),
(15, 6, 'policeAdmin25', 'linuxadmrwl9125a', '1', '1', '1', '1'),
(16, 7, 'posteur', 'posteur', '1', '1', '0', '0'),
(17, 7, 'test', 'test', '1', '1', '1', '1'),
(30, 7, 'invite', 'invite', '1', '0', '0', '0'),
(31, 7, 'flic', 'flic', '1', '1', '1', '0'),
(32, 7, 'Admin', 'password', '1', '1', '1', '1'),
(33, 8, 'administrateur', 'rwl9125alinuxadm', '1', '1', '1', '1'),
(34, 8, 'K.Koenig', 'Hannover2020', '1', '1', '1', '1'),
(36, 9, 'Marche', 'Citoyenne', '1', '1', '1', '1'),
(37, 10, 'Marche', 'Citoyenne', '1', '1', '1', '1'),
(38, 8, 'S.Tatsuwaru', '159764', '0', '0', '0', '0'),
(40, 11, 'admin', '19121986', '1', '1', '1', '1'),
(44, 11, 'hvedrung', '19121986', '1', '1', '1', '0'),
(45, 11, 'tanaka', 'cp1afq46', '1', '0', '1', '0'),
(46, 11, 'liang', 'dv9jsn6n', '1', '0', '1', '0'),
(47, 11, 'marley', 'zed8xgck', '1', '0', '1', '0'),
(48, 11, 'zerratul', 'h9yba27b', '1', '0', '1', '0'),
(49, 11, 'blackberg', 'n2pk6ouy', '1', '0', '1', '0'),
(50, 11, 'scobald', 'w4r9av1n', '1', '0', '1', '0'),
(53, 11, 'warren', 'ews1uup6', '1', '0', '1', '0'),
(54, 11, 'sullivan', 'n5vs8kse', '1', '0', '1', '0'),
(55, 8, 'L.Caloway', ' sim5489', '1', '1', '1', '1'),
(56, 8, 'N.Doe', '170781', '1', '1', '1', '0'),
(57, 8, 'C.Savage', '61332248', '1', '1', '1', '0'),
(58, 8, 'G.Costa', 'aadsdf233', '1', '1', '1', '1'),
(59, 8, 'A.Triana', '1478963', '1', '1', '1', '0'),
(61, 8, 'B.Hyffigeshpiah', '3698741', '1', '1', '1', '0'),
(62, 12, 'Julie', 'V1074R3', '1', '1', '1', '1'),
(63, 13, 'mvz', 'Ernesto', '1', '1', '1', '1'),
(64, 12, 'Maritza', 'M4R1TZ4', '1', '1', '1', '1'),
(65, 14, 'annuaire', 'dn09091972ok', '1', '1', '1', '1'),
(66, 14, 'visiteur', 'visiteur', '1', '0', '0', '0'),
(67, 15, 'Lorne Oldspring', 'Springer', '1', '1', '1', '1'),
(68, 14, '', '', '0', '0', '0', '0'),
(69, 14, '', '', '0', '0', '0', '0'),
(70, 14, '', '', '0', '0', '0', '0'),
(71, 16, 'New', 'flobelle', '1', '1', '1', '1'),
(72, 16, 'Boss', 'triangule', '1', '1', '1', '0');
