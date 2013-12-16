<?php

class Hook_After_Group_Create extends Psa_Hook_After_Group_Create{

	function psa_main($group){
		if($group instanceof Psa_Group)
			throw new Exception('Hook_After_Group_Create');

		throw new Exception('Error: Hook_After_Group_Create');
	}
}
