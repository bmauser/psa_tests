<?php

class Hook_After_User_Delete extends Psa_Hook_After_User_Delete{

	function psa_main($user){
		if($user == 2)
			throw new Exception('Hook_After_User_Delete');

		throw new Exception('Error: Hook_After_User_Delete');
	}
}
