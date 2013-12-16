<?php

class Hook_After_Group_Delete extends Psa_Hook_After_Group_Delete{

	function psa_main($group){
		if($group == 1)
			throw new Exception('Hook_After_Group_Delete');

		throw new Exception('Error: Hook_After_Group_Delete');
	}
}
