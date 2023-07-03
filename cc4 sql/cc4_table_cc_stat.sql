
-- --------------------------------------------------------

--
-- Structure de la table `cc_stat`
--

DROP TABLE IF EXISTS `cc_stat`;
CREATE TABLE `cc_stat` (
  `id` smallint(2) UNSIGNED NOT NULL,
  `abbr` varchar(3) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_stat`
--

INSERT INTO `cc_stat` (`id`, `abbr`, `nom`, `description`) VALUES
(1, 'agi', 'Agilité', 'La capacité de votre personnage à se mouvoir avec aisance, escalader les gouttières, et survivre au Kamasutra.'),
(2, 'dex', 'Dextérité', 'Représente la faculté de votre personnage à savoir faire quelque chose de ses dix doigts. Que ce soit pour réaliser une opération de chirurgie cardiaque avec une cuillère, ou réparer son ordinateur.'),
(3, 'for', 'Force', 'Capacité nervo-musculaire exercée dans tout rapport physique avec votre environnement. Elle détermine la tonicité de votre personnage et lui permet de soulever cette pétoire de rêve qui crache 1000 balles à la minute.'),
(4, 'int', 'Intelligence', 'Ce qui fait la différence entre une carotte et Einstein. Faculté de votre personnage à analyser et déduire d\'une situation problématique pour la résoudre.'),
(5, 'per', 'Perception', '\"- C\'est quoi ce bruit ? - Rien, ta gueule. - C\'est quoi cette odeur ? - J\'ai mangé un kebab ce midi.\" Niveau de sensibilité de votre personnage à son environnement. Mais on dit que la perte de certains sens peut amener à développer ceux restants... ');
