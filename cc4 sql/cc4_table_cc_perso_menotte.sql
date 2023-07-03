
-- --------------------------------------------------------

--
-- Structure de la table `cc_perso_menotte`
--

DROP TABLE IF EXISTS `cc_perso_menotte`;
CREATE TABLE `cc_perso_menotte` (
  `inv_id` int(12) UNSIGNED NOT NULL COMMENT 'menotté par',
  `to_id` int(5) UNSIGNED NOT NULL COMMENT 'sera menotté',
  `expiration` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
