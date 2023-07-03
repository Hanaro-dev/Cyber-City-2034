
-- --------------------------------------------------------

--
-- Structure de la table `cc_sitesweb`
--

DROP TABLE IF EXISTS `cc_sitesweb`;
CREATE TABLE `cc_sitesweb` (
  `id` int(12) NOT NULL,
  `url` varchar(250) NOT NULL,
  `titre` varchar(250) NOT NULL,
  `acces` enum('pub','priv') NOT NULL,
  `first_page` int(12) NOT NULL COMMENT 'Afficher directement une page en plus de l''index'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_sitesweb`
--

INSERT INTO `cc_sitesweb` (`id`, `url`, `titre`, `acces`, `first_page`) VALUES
(1, 'dom.net', 'Dom Net - Fournisseur', 'pub', 1),
(7, 'Police', 'Site Web des Policiers du Dome', 'pub', 5),
(8, 'dww.police.ord', 'Un Ordre, une mission, une institution.', 'pub', 11),
(9, 'lmci.info', 'La Marche Citoyenne - Info', 'pub', 12),
(10, 'mcitoyenne.verite', 'Intranet de La Marche Citoyenne', 'pub', 0),
(11, 'sts.net', 'Section Technique et Scientifique', 'pub', 19),
(12, 'universite_dom.net', 'L\'université du dôme', 'pub', 53),
(13, 'MVasquez', 'Maritza Vasquez', 'pub', 0),
(14, 'annuaire', 'Botin du Dôme', 'pub', 0),
(15, 'Miroir.net', 'Miroir', 'pub', 61),
(16, 'Big_Brother', '', 'pub', 0);
