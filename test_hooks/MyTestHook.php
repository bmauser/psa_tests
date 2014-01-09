<?php

class MyTestHook extends TestHook{

	function psa_main($param){

		throw new Exception($param);
	}
}
