<?php

class Hook_After_User_Authorize extends Psa_Hook_After_User_Authorize{

	function psa_main($user){
		if($user instanceof Psa_User)
			throw new Exception('Hook_After_User_Authorize');

		throw new Exception('Error: Hook_After_User_Authorize');
	}
}
