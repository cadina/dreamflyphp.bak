<?php

class AccountController extends CController
{
	public function RegisterAction()
	{
		return $this->view();
	}
	
	public function RegisterPostAction($form)
	{
		if ($form->validate())
		{
		}
		return $this->view();
	}
}
