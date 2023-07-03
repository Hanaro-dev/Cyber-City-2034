
-- --------------------------------------------------------

--
-- Structure de la table `cc_session`
--

DROP TABLE IF EXISTS `cc_session`;
CREATE TABLE `cc_session` (
  `userId` int(12) DEFAULT NULL,
  `ip` varchar(15) NOT NULL,
  `idcookie` varchar(50) NOT NULL DEFAULT '',
  `expiration` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cc_session`
--

INSERT INTO `cc_session` (`userId`, `ip`, `idcookie`, `expiration`) VALUES
(NULL, '176.138.232.105', '0W0TGQN8FP65CKFELN9R4FW5NLC51QISC9BJPPIV5FQ7QVB18B', '1512517527');
