<?php

class CMethodAction extends CAction
{
	public function execute()
	{
		call_user_func_array(array($this->_controller, $this->_name . 'action'));
	}
}
