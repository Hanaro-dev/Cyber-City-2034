
-- --------------------------------------------------------

--
-- Structure de la table `cc_competence`
--

DROP TABLE IF EXISTS `cc_competence`;
CREATE TABLE `cc_competence` (
  `id` smallint(2) UNSIGNED NOT NULL,
  `abbr` varchar(4) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` tinytext NOT NULL,
  `efface` enum('0','1') NOT NULL COMMENT 'Si la compÃ©tence peut-Ãªtre effacÃ©e par le paneau d''administration',
  `inscription` enum('0','1') NOT NULL COMMENT 'Si la compÃ©tence apparaitra lors de l''inscription'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_competence`
--

INSERT INTO `cc_competence` (`id`, `abbr`, `nom`, `description`, `efface`, `inscription`) VALUES
(2, 'armb', 'Armes blanches', 'La faux, le sabre, le poignard, la machette, le kriss, l\'épée, le glaive, la serpe, ou encore le stylet, bref toutes ces choses qui tranchent ou perforent ça vous plaît !', '0', '1'),
(3, 'armc', 'Corps &agrave; corps', 'Gauche, droite, gauche, droite... Vous, vous aimez la sueur, le ring et le sac de sable. A moins que les combats de rue plus libres conviennent mieux à votre âme de tête brulée.', '0', '1'),
(4, 'armf', 'Armes &agrave; feu', 'Que ça soit la vieille arquebuse de votre arrière-arrière-arrière grand-père ou le dernier Uzi du marché en passant par le petit calibre que l\'on peut trouver aux puces, tout ce qui fait plein de trous et de bruits vous maitrisez.', '0', '1'),
(5, 'arml', 'Armes lourdes', 'Aaaah je vous sens tout excité à l\'idée de pouvoir attaquer tout un régiment avec votre mitrailleuse Gatling. Ou peut-être que vous souhaitez faire exploser la voiture du chef de la Police avec votre lance-roquette ?', '0', '1'),
(6, 'armu', 'Armurier', 'Nettoyer une arme, changer ses pièces, fabriquer ses propres munitions, tout ça c\'est votre rayon! D\'ailleurs, vicieux comme vous êtes, vous allez sans doute élaborer un tout nouveau type de munitions qui transpercera le blindage comme du beurre.', '0', '1'),
(7, 'arti', 'Artisanat', 'Confectionner des bottes, des chapeaux de pailles, des bijoux ou encore des pots en terre cuite que vous pourrez balancer sur les punks, une chose est sûre : vos mains, vous savez vous en servir !', '1', '1'),
(8, 'athl', 'Athl&eacute;tisme', 'Le cent mètres en 9,70s ? Semer la police à pattes ? Tout ça, c\'est dans vos cordes.', '1', '1'),
(9, 'chim', 'Chimie', 'Qu\'allez-vous concocter ? De l\'acide ? Un truc pour déboucher les toilettes du bar d\'à côté ? Ouvrir un laboratoire pour élaborer de nouvelles drogues ?', '1', '1'),
(11, 'croc', 'Crochetage', 'Plus aucune serrure n\'aura de secret pour vous... surtout pas celle de votre voisin qui a cette magnifique TV HD.', '1', '1'),
(13, 'agro', 'Agronomie', 'Vous avez la main verte ?', '1', '1'),
(14, 'cybr', 'Cybern&eacute;tique', 'Le Corps est un organe dérangeant, seuls l\'Esprit et la Machine sont immortels.', '1', '1'),
(15, 'drsg', 'Dressage', 'Votre côté dominateur se voit parfaitement, et vous savez le faire sentir aux bêtes.', '1', '0'),
(16, 'elec', 'Electronique', 'Les circuits imprimés n\'ont pas de secret pour vous, vous adorez jouer aux labyrinthes avec, en retrouvant la sortie, et à l\'occasion vous les soudez même ensemble pour faire marcher tout un tas d\'appareils tout à fait indispensables.', '1', '1'),
(17, 'ensg', 'Enseignement', 'Vous aimez les réformes ? Un emploi instable ne vous fait pas plus peur qu\'une classe de petits monstres incultes ? Alors ce métier est fait pour vous.', '1', '1'),
(18, 'esqv', 'Esquive', 'Prendre des gnons c\'est pas votre truc, vous préférez épuiser votre adversaire en évitant chacun de ses coups.', '0', '1'),
(19, 'expl', 'Explosifs', 'Dynamite, C4, semtex, TNT, nitroglycérine, tout ça vous connaissez mais il peut s\'avérer difficile de s\'en procurer dans le Dôme. Alors place aux autres joyeusetés comme des bombes artisanales encore méconnues des services de Police !', '0', '1'),
(20, 'forg', 'Forge', 'Le feu et le métal. Revenez à la source de toute civilisation en façonnant de vos mains les objets de la nouvelle ère.', '0', '1'),
(21, 'frtv', 'Furtivit&eacute;', 'Se déplacer sans trébucher sur une bouteille de bière qui traine par terre est tout un art dans le Dôme.', '1', '1'),
(25, 'lncr', 'Lancer', 'Reproduisez Mai 68 en jetant les pavés du Dôme sur la tête de ceux qui ne vous reviennent pas !', '0', '1'),
(26, 'meca', 'M&eacute;canique', 'Le cambouis et l\'huile ne vous font pas peur, vous adorez les mécanismes qui tournent ronds, la clef de douze est votre amie... Que dire de plus ?', '1', '1'),
(27, 'mrch', 'Marchandage', 'Baissez systématiquement le prix de vos achats en prétendant que vous avez une famille très nombreuse et qu\'après tout les articles de votre vendeur ne sont pas aussi bien qu\'il le prétend.', '1', '1'),
(28, 'pckp', 'Pickpocket', 'Glisser habilement ses doigts dans les poches des passants sans se faire sectionner la main n\'est pas donné à tout le monde.', '0', '1'),
(29, 'pltg', 'Pilotage', 'Vous ferez corps avec tout ce qui fait vroum, teuf teuf ou tougoudougoudou.', '1', '0'),
(30, 'info', 'Informatique', 'Geeks, transformez votre vice en qualité.', '1', '1'),
(31, 'psyc', 'Psychologie', 'Soigner les fous et les déficients mentaux ça vous passionne. Vous allez surement adorer les récits du dingue qui a découpé un enfant de quatre ans en rondelles avant de s\'en servir comme garniture pour sa soirée crêpe.', '1', '1'),
(32, 'meds', 'M&eacute;decine', 'Faire du bouche à bouche à la jolie blonde qui vient de faire un malaise ou compresser les plaies d\'un passant qui n\'a pas eu de chance en rencontrant une machette, oui, oui, tout ça vous aimez et le maitrisez.', '1', '1');
