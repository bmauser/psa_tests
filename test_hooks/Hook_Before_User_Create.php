<?php

class Hook_Before_User_Create extends Psa_Hook_Before_User_Create{

	function psa_main($user){
		if($user instanceof Psa_User)
			throw new Exception('Hook_Before_User_Create');

		throw new Exception('Error: Hook_Before_User_Create');
	}
}
