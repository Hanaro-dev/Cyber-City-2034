
-- --------------------------------------------------------

--
-- Structure de la table `cc_mairie_question`
--

DROP TABLE IF EXISTS `cc_mairie_question`;
CREATE TABLE `cc_mairie_question` (
  `id` int(12) NOT NULL,
  `section` smallint(2) NOT NULL,
  `question` text NOT NULL,
  `reponse_tech` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_mairie_question`
--

INSERT INTO `cc_mairie_question` (`id`, `section`, `question`, `reponse_tech`) VALUES
(1, 1, 'Quelle est le secteur où les flics sont encore influents ?', 'c'),
(2, 1, 'A quoi sert le Visa Vert ?', 'a'),
(3, 1, 'Quelle groupe est dirigé par le Juge  ? ', 'a'),
(4, 1, 'Quels sont trois des grands groupes connus de Cybercity ?', 'c'),
(5, 1, 'Que se passe-t-il pour les personnages de moins de 18 ans dans le dôme ?', 'c'),
(6, 1, 'Quel est l\'intérêt de choisir rapidement un groupe d\'appartenance ?', 'b'),
(7, 1, 'Les armes sont elles autorisées dans le dôme ?', 'a'),
(8, 2, 'Une attaque doit être suivie...', 'a'),
(9, 2, 'Que signifie EJ et HJ ?', 'b'),
(10, 2, 'Pour écrire un message hors sujet (sans rapport avec son personnage) dans l\'Historique des Evènements, quel format est recommandé ?', 'a'),
(11, 2, 'A quoi fait référence \"une remise\" dans le jeu ?', 'b'),
(12, 2, 'Que signifie PR ?', 'c'),
(13, 2, 'Que sont les PN ?', 'b'),
(14, 2, 'Que devez-vous faire en cas de détection d\'un bug ?', 'c'),
(15, 2, 'A quoi sert la fonction PPA ?', 'b'),
(16, 2, 'Que dois-je faire si je pars en vacances ?', 'c'),
(17, 2, 'Je pense avoir été victime d\'une forme de non-RP ou d\'une tricherie, que faire?', 'c'),
(18, 3, 'Que s\'est il passé le 14 octobre 2034 ?', 'b'),
(19, 3, 'Qu\'est ce qu\'une Maroxy ?', 'a'),
(20, 3, 'Qu\'est-ce qui a détruit l\'Ordre, état totalitaire du Dôme ?', 'c'),
(21, 3, 'Quand est ce que le Dôme a été terminé ?', 'c'),
(22, 3, 'Où se trouve le Dôme?', 'a'),
(23, 3, 'Pourquoi le monde est il devenu ainsi ?', 'c'),
(24, 3, 'Qui est à la tête du pouvoir en place ?', 'c'),
(25, 3, 'Qui est à l\'origine de la construction du Dôme ?', 'b');
