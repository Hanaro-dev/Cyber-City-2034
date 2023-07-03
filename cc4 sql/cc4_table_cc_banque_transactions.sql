
-- --------------------------------------------------------

--
-- Structure de la table `cc_banque_transactions`
--

DROP TABLE IF EXISTS `cc_banque_transactions`;
CREATE TABLE `cc_banque_transactions` (
  `transaction_id` int(12) NOT NULL,
  `transaction_compte_from` int(12) NOT NULL,
  `transaction_compte_to` int(12) NOT NULL,
  `transaction_valeur` int(12) NOT NULL,
  `transaction_description` varchar(50) NOT NULL,
  `transaction_date` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
