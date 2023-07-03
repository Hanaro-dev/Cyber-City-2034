
-- --------------------------------------------------------

--
-- Structure de la table `cc_media`
--

DROP TABLE IF EXISTS `cc_media`;
CREATE TABLE `cc_media` (
  `id` int(12) NOT NULL,
  `mediaType` enum('radio','tele') NOT NULL,
  `canalId` int(12) NOT NULL,
  `date` int(10) UNSIGNED NOT NULL,
  `titre` text NOT NULL,
  `message` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
