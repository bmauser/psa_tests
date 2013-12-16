<?php

class Hook_Before_User_Delete extends Psa_Hook_Before_User_Delete{

	function psa_main($user){
		if($user == 2)
			throw new Exception('Hook_Before_User_Delete');

		throw new Exception('Error: Hook_Before_User_Delete');
	}
}
