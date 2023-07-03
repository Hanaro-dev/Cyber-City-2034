
-- --------------------------------------------------------

--
-- Structure de la table `cc_banque_comptes`
--

DROP TABLE IF EXISTS `cc_banque_comptes`;
CREATE TABLE `cc_banque_comptes` (
  `compte_id` int(12) NOT NULL,
  `compte_idperso` int(12) NOT NULL DEFAULT '0',
  `compte_nom` varchar(50) NOT NULL,
  `compte_banque` int(4) NOT NULL DEFAULT '0',
  `compte_compte` varchar(14) NOT NULL,
  `compte_cash` int(12) NOT NULL DEFAULT '0',
  `compte_nip` int(5) NOT NULL DEFAULT '0',
  `compte_auth_auto_transaction` smallint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_banque_comptes`
--

INSERT INTO `cc_banque_comptes` (`compte_id`, `compte_idperso`, `compte_nom`, `compte_banque`, `compte_compte`, `compte_cash`, `compte_nip`, `compte_auth_auto_transaction`) VALUES
(275, 889, 'Paul smith', 3727, '2934-4737-5471', 2500, 0, 0),
(347, 1110, 'Cassidy Armani', 3727, '5160-5728-6140', 4600, 12354, 1),
(350, 1110, 'Cassidy Armani', 5878, '2626-4507-6443', 94000, 12354, 0),
(404, 1423, 'Marie-Chasteté Davis', 3727, '7527-0813-2788', 400, 1234, 0),
(436, 1585, 'Alexanne Chevalier', 3727, '0530-9508-4431', 2800, 3000, 0),
(438, 1607, 'Rodrigo Alvarés', 3727, '1175-3505-3448', 1400, 24124, 0),
(441, 1714, 'Vegas Deckard', 3727, '9815-7638-7714', 1000, 1664, 0),
(443, 1670, 'Deirdre Van Strauss', 3727, '5450-3160-2306', 29370, 3767, 0),
(455, 1839, 'Ada Erasom', 3727, '8656-6439-8394', 9400, 1701, 0),
(456, 1949, 'Ally Cooper', 3727, '0415-5997-3733', 0, 1704, 0),
(457, 1791, 'Thunder Crock', 3727, '9635-2198-7277', 1000, 54321, 0);
