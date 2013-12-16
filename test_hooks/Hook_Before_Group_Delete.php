<?php

class Hook_Before_Group_Delete extends Psa_Hook_Before_Group_Delete{

	function psa_main($group){
		if($group == 2)
			throw new Exception('Hook_Before_Group_Delete');

		throw new Exception('Error: Hook_Before_Group_Delete');
	}
}
