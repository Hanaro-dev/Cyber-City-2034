
-- --------------------------------------------------------

--
-- Structure de la table `cc_log_mp`
--

DROP TABLE IF EXISTS `cc_log_mp`;
CREATE TABLE `cc_log_mp` (
  `id` int(12) NOT NULL,
  `userId` int(12) NOT NULL,
  `date` int(12) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `email` varchar(150) NOT NULL,
  `item` varchar(15) NOT NULL,
  `statusPP` tinytext NOT NULL,
  `statusCC` tinytext NOT NULL,
  `post` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
