<?php

class Hook_Before_Group_Create extends Psa_Hook_Before_Group_Create{

	function psa_main($group){
		if($group instanceof Psa_Group)
			throw new Exception('Hook_Before_Group_Create');

		throw new Exception('Error: Hook_Before_Group_Create');
	}
}
