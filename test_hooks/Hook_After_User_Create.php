<?php

class Hook_After_User_Create extends Psa_Hook_After_User_Create{

	function psa_main($user){
		if($user instanceof Psa_User)
			throw new Exception('Hook_After_User_Create');

		throw new Exception('Error: Hook_After_User_Create');
	}
}
