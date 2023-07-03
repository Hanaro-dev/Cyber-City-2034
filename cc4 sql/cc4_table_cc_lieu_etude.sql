
-- --------------------------------------------------------

--
-- Structure de la table `cc_lieu_etude`
--

DROP TABLE IF EXISTS `cc_lieu_etude`;
CREATE TABLE `cc_lieu_etude` (
  `lieuId` mediumint(8) UNSIGNED NOT NULL,
  `comp` enum('ARMB','ARMC','ARMF','ARML','ARMU','ARTI','ATHL','CHIM','CROC','CUIS','CYBR','DRSG','ELEC','ENSG','ESQV','EXPL','FORG','FRTV','INFO','LNCR','MECA','MEDS','MRCH','PCKP','PLTG','PSYC') NOT NULL DEFAULT 'ARMB',
  `cout_cash` int(8) NOT NULL DEFAULT '0' COMMENT 'Cout Cash / 1 tour',
  `cout_pa` int(8) NOT NULL DEFAULT '3' COMMENT 'Cout PA / 1 tour',
  `qualite_lieu` tinyint(3) NOT NULL DEFAULT '70' COMMENT 'Ambience du lieu propice à l''étude'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_lieu_etude`
--

INSERT INTO `cc_lieu_etude` (`lieuId`, `comp`, `cout_cash`, `cout_pa`, `qualite_lieu`) VALUES
(139, 'ATHL', 0, 3, 70),
(141, 'CYBR', 20, 3, 70),
(141, 'ELEC', 20, 3, 70),
(141, 'ENSG', 20, 3, 70),
(141, 'INFO', 20, 3, 70),
(141, 'MECA', 20, 3, 70),
(165, 'ARTI', 0, 3, 70),
(165, 'MRCH', 0, 3, 70),
(181, 'ARMB', 0, 3, 70),
(181, 'ARMC', 0, 3, 70),
(181, 'ESQV', 0, 3, 70),
(194, 'ATHL', 0, 3, 70),
(196, '', 0, 3, 70),
(601, 'ARTI', 0, 3, 70),
(601, 'FORG', 0, 3, 70),
(1126, 'CHIM', 0, 3, 70),
(1126, 'EXPL', 0, 3, 70),
(1128, 'CYBR', 0, 3, 70),
(1128, 'MEDS', 0, 3, 70),
(1128, 'PSYC', 0, 3, 70),
(1131, 'ARMU', 0, 3, 70),
(1133, 'ARMC', 0, 3, 70),
(1133, 'ESQV', 0, 3, 70),
(1133, 'PCKP', 0, 3, 70),
(1135, 'ARMB', 0, 3, 70),
(1135, 'ARMC', 0, 3, 70),
(1135, 'ARMF', 0, 3, 70),
(1135, 'ARML', 0, 3, 70),
(1135, 'ARMU', 0, 3, 70),
(1135, 'ESQV', 0, 3, 70),
(1135, 'LNCR', 0, 3, 70),
(1136, 'EXPL', 0, 3, 70),
(1139, 'ARMB', 0, 3, 70),
(1139, 'ARMC', 0, 3, 70),
(1139, 'ARMF', 0, 3, 70),
(1139, 'ARML', 0, 3, 70),
(1139, 'ESQV', 0, 3, 70),
(1139, 'LNCR', 0, 3, 70),
(1140, 'ARMF', 0, 3, 70),
(1140, 'ARML', 0, 3, 70),
(1140, 'ARMU', 0, 3, 70),
(1140, 'ESQV', 0, 3, 70),
(1141, 'ARMB', 0, 3, 70),
(1141, 'ARMC', 0, 3, 70),
(1141, 'LNCR', 0, 3, 70),
(1143, 'ARTI', 0, 3, 70),
(1144, 'FRTV', 0, 3, 70),
(1146, 'CROC', 0, 3, 70),
(1146, 'FRTV', 0, 3, 70),
(1147, 'CROC', 0, 3, 70),
(1147, 'FRTV', 0, 3, 70),
(1151, 'ARMC', 0, 10, 70),
(1151, 'INFO', 0, 10, 70),
(1155, 'ARMB', 0, 3, 70),
(1155, 'ARMC', 0, 3, 70),
(1155, 'ARMF', 0, 3, 70),
(1155, 'ARML', 20, 3, 70),
(1155, 'ARMU', 0, 3, 70),
(1155, 'ESQV', 0, 3, 70),
(1155, 'LNCR', 0, 3, 70),
(1170, 'ARMU', 0, 3, 70),
(1180, 'ARMB', 0, 3, 70),
(1211, 'CROC', 0, 3, 70),
(1211, 'FRTV', 0, 3, 70),
(1211, 'MRCH', 0, 3, 70),
(1211, 'PCKP', 0, 3, 7);
