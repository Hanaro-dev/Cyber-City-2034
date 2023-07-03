
-- --------------------------------------------------------

--
-- Structure de la table `cc_item_menu`
--

DROP TABLE IF EXISTS `cc_item_menu`;
CREATE TABLE `cc_item_menu` (
  `id` int(12) NOT NULL,
  `item_dbid` int(12) NOT NULL DEFAULT '0',
  `caption` varchar(30) NOT NULL,
  `url` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_item_menu`
--

INSERT INTO `cc_item_menu` (`id`, `item_dbid`, `caption`, `url`) VALUES
(15, 73, 'Telephoner', 'Telephoner'),
(16, 38, 'Telephoner', 'Telephoner'),
(19, 20, 'Informatique', 'Ordinateur'),
(20, 97, 'Sac à dos', 'Sac'),
(22, 255, '3G+', 'Ordinateur'),
(23, 253, 'Ordinateur', 'Ordinateur'),
(24, 254, 'Ordinateur', 'Ordinateur'),
(25, 256, 'Sacoche', 'Sac'),
(26, 257, 'Sac à dos', 'Sac'),
(27, 258, 'Sac à dos', 'Sac'),
(28, 266, 'Menotter', 'Menotter'),
(29, 255, 'Telephoner', 'Telephoner'),
(31, 441, 'sacoche', 'Sac'),
(37, 259, 'Sac à dos', 'Sac'),
(41, 653, 'Holster', 'Sac'),
(43, 698, '', 'Ordinateur'),
(58, 922, 'Talkie Walkie', 'Radios'),
(59, 923, 'Talkie Walkie', 'Radios'),
(60, 924, 'Talkie Walkie', 'Radios'),
(62, 993, 'Baluchon', 'Sac'),
(63, 995, 'Téléphoner', 'Telephoner'),
(64, 995, '3G+', 'Ordinateur'),
(65, 874, 'Ouvrir le carton', 'Sac'),
(71, 1125, 'Malette', 'Sac'),
(72, 1282, 'Mallette ', 'Sac'),
(74, 1290, 'chariot', 'Sac'),
(75, 1295, 'Fourreaux', 'Sac'),
(77, 1348, 'Auto-Injecteur FS 18-93', 'Sac'),
(78, 1349, 'Holster Cyb-Auto', 'Sac'),
(79, 1350, 'Cache incorporé Modèle BY', 'Sac'),
(80, 1351, 'Cy-Phone', 'Telephoner'),
(81, 1352, 'Cyber-Transmetteur', 'Radios'),
(82, 1400, 'Média radio', 'Media'),
(84, 1401, 'Média radio', 'Media'),
(85, 1418, 'Brouette', 'Sac'),
(86, 1419, 'Ceinture à poches', 'Sac');
