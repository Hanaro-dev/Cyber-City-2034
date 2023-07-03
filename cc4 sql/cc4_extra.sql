
--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cc_account`
--
ALTER TABLE `cc_account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`,`email`),
  ADD KEY `remise_tag` (`remise_tag`);

--
-- Index pour la table `cc_banque`
--
ALTER TABLE `cc_banque`
  ADD PRIMARY KEY (`banque_id`),
  ADD KEY `no` (`banque_no`),
  ADD KEY `lieu` (`banque_lieu`);

--
-- Index pour la table `cc_banque_cartes`
--
ALTER TABLE `cc_banque_cartes`
  ADD PRIMARY KEY (`carte_id`);

--
-- Index pour la table `cc_banque_comptes`
--
ALTER TABLE `cc_banque_comptes`
  ADD PRIMARY KEY (`compte_id`),
  ADD KEY `compte_compte` (`compte_compte`);

--
-- Index pour la table `cc_banque_historique`
--
ALTER TABLE `cc_banque_historique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account` (`compte`);

--
-- Index pour la table `cc_banque_transactions`
--
ALTER TABLE `cc_banque_transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Index pour la table `cc_boutiques_gerants`
--
ALTER TABLE `cc_boutiques_gerants`
  ADD PRIMARY KEY (`persoid`,`boutiqueid`),
  ADD KEY `persoid` (`persoid`);

--
-- Index pour la table `cc_boutiques_historiques`
--
ALTER TABLE `cc_boutiques_historiques`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cc_caract`
--
ALTER TABLE `cc_caract`
  ADD PRIMARY KEY (`id`),
  ADD KEY `catId` (`catid`);

--
-- Index pour la table `cc_caract_incompatible`
--
ALTER TABLE `cc_caract_incompatible`
  ADD PRIMARY KEY (`id1`,`id2`),
  ADD KEY `id2` (`id2`);

--
-- Index pour la table `cc_casino`
--
ALTER TABLE `cc_casino`
  ADD PRIMARY KEY (`casino_id`),
  ADD KEY `lieu` (`casino_lieu`);

--
-- Index pour la table `cc_competence`
--
ALTER TABLE `cc_competence`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `abbr` (`abbr`);

--
-- Index pour la table `cc_competence_stat`
--
ALTER TABLE `cc_competence_stat`
  ADD PRIMARY KEY (`compid`,`statid`),
  ADD KEY `statid` (`statid`);

--
-- Index pour la table `cc_dev_copie_lieu`
--
ALTER TABLE `cc_dev_copie_lieu`
  ADD PRIMARY KEY (`old_id`,`new_id`);

--
-- Index pour la table `cc_he`
--
ALTER TABLE `cc_he`
  ADD PRIMARY KEY (`id`),
  ADD KEY `date` (`date`);

--
-- Index pour la table `cc_he_description`
--
ALTER TABLE `cc_he_description`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cc_he_fromto`
--
ALTER TABLE `cc_he_fromto`
  ADD PRIMARY KEY (`persoid`,`show`,`msgid`,`fromto`),
  ADD KEY `msgid` (`msgid`);

--
-- Index pour la table `cc_item_db`
--
ALTER TABLE `cc_item_db`
  ADD PRIMARY KEY (`db_id`),
  ADD KEY `db_type` (`db_type`,`db_soustype`,`db_nom`);

--
-- Index pour la table `cc_item_db_armemunition`
--
ALTER TABLE `cc_item_db_armemunition`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_armeid` (`db_armeid`,`db_munitionid`);

--
-- Index pour la table `cc_item_inv`
--
ALTER TABLE `cc_item_inv`
  ADD PRIMARY KEY (`inv_id`),
  ADD KEY `inv_dbid` (`inv_dbid`),
  ADD KEY `inv_persoid` (`inv_persoid`),
  ADD KEY `inv_lieutech` (`inv_lieutech`),
  ADD KEY `inv_boutiquelieutech` (`inv_boutiquelieutech`),
  ADD KEY `inv_itemid` (`inv_itemid`),
  ADD KEY `inv_idcasier` (`inv_idcasier`);

--
-- Index pour la table `cc_item_menu`
--
ALTER TABLE `cc_item_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lieutech` (`item_dbid`);

--
-- Index pour la table `cc_lieu`
--
ALTER TABLE `cc_lieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nom_technique` (`nom_technique`);

--
-- Index pour la table `cc_lieu_ban`
--
ALTER TABLE `cc_lieu_ban`
  ADD PRIMARY KEY (`id`),
  ADD KEY `persoid` (`persoid`);

--
-- Index pour la table `cc_lieu_casier`
--
ALTER TABLE `cc_lieu_casier`
  ADD PRIMARY KEY (`id_casier`),
  ADD KEY `lieuId` (`lieuId`);

--
-- Index pour la table `cc_lieu_distributeur`
--
ALTER TABLE `cc_lieu_distributeur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lieuId` (`lieuId`,`producteurId`),
  ADD KEY `producteurId` (`producteurId`);

--
-- Index pour la table `cc_lieu_etude`
--
ALTER TABLE `cc_lieu_etude`
  ADD PRIMARY KEY (`lieuId`,`comp`);

--
-- Index pour la table `cc_lieu_lien`
--
ALTER TABLE `cc_lieu_lien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from` (`from`);

--
-- Index pour la table `cc_lieu_livre`
--
ALTER TABLE `cc_lieu_livre`
  ADD PRIMARY KEY (`lieuId`,`itemDbId`),
  ADD KEY `itemDbId` (`itemDbId`);

--
-- Index pour la table `cc_lieu_medias`
--
ALTER TABLE `cc_lieu_medias`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cc_lieu_menu`
--
ALTER TABLE `cc_lieu_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lieutech` (`lieutech`);

--
-- Index pour la table `cc_lieu_tenirporte`
--
ALTER TABLE `cc_lieu_tenirporte`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vers` (`vers`,`qui`),
  ADD KEY `qui` (`qui`),
  ADD KEY `expiration` (`expiration`),
  ADD KEY `de` (`de`);

--
-- Index pour la table `cc_log_conn`
--
ALTER TABLE `cc_log_conn`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cc_log_mp`
--
ALTER TABLE `cc_log_mp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `userId` (`userId`);

--
-- Index pour la table `cc_log_persomort`
--
ALTER TABLE `cc_log_persomort`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timestamp` (`timestamp`);

--
-- Index pour la table `cc_log_persosuppr`
--
ALTER TABLE `cc_log_persosuppr`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cc_log_telephone`
--
ALTER TABLE `cc_log_telephone`
  ADD PRIMARY KEY (`id_he_exp`),
  ADD KEY `from_tel` (`from_tel`);

--
-- Index pour la table `cc_mairie_question`
--
ALTER TABLE `cc_mairie_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section` (`section`);

--
-- Index pour la table `cc_mairie_question_reponse`
--
ALTER TABLE `cc_mairie_question_reponse`
  ADD PRIMARY KEY (`questionId`,`reponse_tech`);

--
-- Index pour la table `cc_media`
--
ALTER TABLE `cc_media`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cc_mj`
--
ALTER TABLE `cc_mj`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userId` (`userId`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Index pour la table `cc_mj_he`
--
ALTER TABLE `cc_mj_he`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mjId` (`mjId`);

--
-- Index pour la table `cc_perso`
--
ALTER TABLE `cc_perso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Index pour la table `cc_perso_caract`
--
ALTER TABLE `cc_perso_caract`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `perso_caract` (`persoid`,`caractid`);

--
-- Index pour la table `cc_perso_competence`
--
ALTER TABLE `cc_perso_competence`
  ADD PRIMARY KEY (`persoid`,`compid`),
  ADD KEY `compid` (`compid`);

--
-- Index pour la table `cc_perso_connu`
--
ALTER TABLE `cc_perso_connu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `persoid` (`persoid`),
  ADD KEY `nomid` (`nomid`);

--
-- Index pour la table `cc_perso_fouille`
--
ALTER TABLE `cc_perso_fouille`
  ADD PRIMARY KEY (`fromid`,`toid`),
  ADD KEY `expiration` (`expiration`),
  ADD KEY `toid` (`toid`);

--
-- Index pour la table `cc_perso_menotte`
--
ALTER TABLE `cc_perso_menotte`
  ADD PRIMARY KEY (`inv_id`,`to_id`),
  ADD KEY `expiration` (`expiration`),
  ADD KEY `to_id` (`to_id`);

--
-- Index pour la table `cc_perso_stat`
--
ALTER TABLE `cc_perso_stat`
  ADD PRIMARY KEY (`persoid`,`statid`),
  ADD KEY `statid` (`statid`);

--
-- Index pour la table `cc_ppa`
--
ALTER TABLE `cc_ppa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parentid` (`mjid`);

--
-- Index pour la table `cc_ppa_reponses`
--
ALTER TABLE `cc_ppa_reponses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sujetid` (`sujetid`,`date`);

--
-- Index pour la table `cc_producteur`
--
ALTER TABLE `cc_producteur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lieuId` (`lieuId`);

--
-- Index pour la table `cc_producteur_inv`
--
ALTER TABLE `cc_producteur_inv`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `producteurId_2` (`producteurId`,`itemDbId`),
  ADD KEY `itemDbId` (`itemDbId`);

--
-- Index pour la table `cc_session`
--
ALTER TABLE `cc_session`
  ADD PRIMARY KEY (`idcookie`),
  ADD KEY `userId` (`userId`);

--
-- Index pour la table `cc_sitesweb`
--
ALTER TABLE `cc_sitesweb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url` (`url`);

--
-- Index pour la table `cc_sitesweb_acces`
--
ALTER TABLE `cc_sitesweb_acces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_id` (`site_id`);

--
-- Index pour la table `cc_sitesweb_pages`
--
ALTER TABLE `cc_sitesweb_pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `msg_parentid` (`msg_parentid`);

--
-- Index pour la table `cc_sitesweb_pages_acces`
--
ALTER TABLE `cc_sitesweb_pages_acces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`);

--
-- Index pour la table `cc_stat`
--
ALTER TABLE `cc_stat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `abbr` (`abbr`);
