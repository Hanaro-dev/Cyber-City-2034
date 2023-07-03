<?php
/*
 *Routine effectuant les remises
 *
 * @author Francois Mazerolle <admin@maz-concept.com
 * @copyright Copyright (c) 2009, Francois Mazerolle
 * @version 1.0
 * @package CronTask
 *
 *dernière version du 18/10/2015 par Balam
 */

require_once('_system.php');

Remise::doRemise($db, 100);
