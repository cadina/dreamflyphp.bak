<?php

/**
 * CEvent
 * Event that implements the observer pattern
 * 
 * @author cadina
 */
final class CEvent extends CBase implements IEvent
{
	private $_subscriptions = array();
	
	public function __invoke()
	{
		$arguments = func_get_args();
		$this->raise_array($arguments);
	}
	
	public function add($callback)
	{
		if (!is_callable($callback));
		
		$this->_subscriptions[] = $callback;
	}
	
	public function remove($callback)
	{
		if (!is_callable($callback));
		if (!($key = in_array($callback, $this->_subscriptions)));
		
		unset($this->_subscription[$key]);
	}
	
	public function invoke()
	{
		$arguments = func_get_args();
		$this->raise_array($arguments);
	}
	
	protected function raise_array($arguments)
	{
		foreach ($this->_subscriptions as $callback)
		{
			call_user_func_array($callback, $arguments);
		}
	}
}
