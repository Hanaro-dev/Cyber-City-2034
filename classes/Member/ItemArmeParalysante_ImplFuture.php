<?php
/** Gestion des armes paralysantes.
*
* @package Member
*/
class Member_ItemArmeParalysante extends Member_ItemArme {
	
	function __construct (&$arr){
		parent::__construct($arr);
		
	}
	
	/** Retourne le type affichable de l'item (pour l'affichage)
	* @return string
	*/	
	public function getType()			{ return 'Arme Paralysante'; }
	
	/** Retourne le type (nom technique) de l'item (pour l'affichage)
	* @return string
	*/	
	public function getTypeTech()		{ return 'arme_paralysante'; }
}

?>