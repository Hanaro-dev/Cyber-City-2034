
-- --------------------------------------------------------

--
-- Structure de la table `cc_banque_cartes`
--

DROP TABLE IF EXISTS `cc_banque_cartes`;
CREATE TABLE `cc_banque_cartes` (
  `carte_id` int(12) NOT NULL COMMENT 'nocarte',
  `carte_banque` varchar(4) NOT NULL,
  `carte_compte` varchar(14) NOT NULL,
  `carte_nom` varchar(25) NOT NULL,
  `carte_nip` int(5) NOT NULL DEFAULT '0',
  `carte_valid` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_banque_cartes`
--

INSERT INTO `cc_banque_cartes` (`carte_id`, `carte_banque`, `carte_compte`, `carte_nom`, `carte_nip`, `carte_valid`) VALUES
(24, '5878', '1636-2709-5557', 'Hannah Smith', 1313, 1),
(25, '5878', '6536-5439-0792', 'Douglas Flint', 8789, 1),
(29, '5878', '2709-5389-7833', 'Nionnus Doe', 113, 1),
(31, '3727', '4594-9125-5627', 'Nionnud Doe', 113, 1),
(39, '5878', '3008-4426-7013', 'Kelly Keller', 3078, 1),
(43, '5878', '8528-5534-2712', 'Maritza Vasquez', 16913, 1),
(44, '3727', '6535-0598-7309', 'Douglas Flint', 42, 1),
(46, '5878', '7832-7658-8898', 'Alexandra Teagan', 11979, 1),
(50, '3727', '3417-6345-6255', 'Petit Saïgon', 9972, 1),
(51, '3727', '3417-6345-6255', 'Erna Liang', 9972, 1),
(55, '5878', '3435-1655-1841', 'Casablanka', 55562, 1),
(58, '5878', '2971-3359-1432', 'Wilson J.', 605, 1),
(67, '5878', '8241-6667-1684', 'Chris Morgan', 3078, 1),
(89, '5878', '6536-5439-0792', 'Douglas Flint', 8789, 1),
(105, '5878', '5030-1164-6778', 'ERNA', 9972, 1),
(106, '5878', '8716-4072-8014', 'Wiliamsson E.', 605, 1),
(108, '3727', '5239-3438-5854', 'Jack Wilson', 605, 1),
(109, '3727', '7048-6164-0459', 'Torreth', 29440, 1),
(110, '5878', '1015-5787-7213', 'Joe Alamo', 4145, 1),
(112, '5878', '8427-2120-7113', 'Tara Knoles', 6748, 1),
(113, '3727', '7489-0504-8216', 'ambrevive', 509, 1),
(114, '3727', '3107-1720-1805', 'Phillip George Mason', 1610, 1),
(115, '3727', '0730-8749-7779', 'Shrapnel', 5666, 1),
(116, '3727', '2294-7995-1319', 'Cigun Rodrigez', 31170, 1),
(117, '3727', '5128-3161-8953', 'Ian Acks', 3767, 1),
(118, '3727', '2157-2864-3235', 'Dray', 1189, 1),
(119, '3727', '5450-3160-2306', 'Deirdre Van Strauss', 3767, 1),
(120, '3727', '9862-5725-5013', 'Tyler Durden', 54321, 1),
(121, '3727', '8076-5451-3494', 'Waltzer Shade', 666, 1),
(122, '3727', '8057-1044-3226', 'Sieg Stolkan', 175, 1),
(123, '3727', '5450-3160-2306', 'Olly', 3767, 1),
(124, '3727', '5450-3160-2306', 'Dee', 3767, 1);
