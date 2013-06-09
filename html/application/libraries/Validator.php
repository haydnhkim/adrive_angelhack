<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validator
{
	function isValidEng123($value)
	{
		if(!ereg("^([a-z &&0-9])$", $value))
		{ 
			return true;
		}
		else
		{
			return false;
		}
	}

}

// End of file validator.php