<?php

/**
 * CComponent
 * Base class of all components that have events and configuration support. 
 *
 * @author cadina
 */
class CComponent extends CBase implements IEventSource, IConfigurable
{

    private $_initialized = false;

    private $_events = array();

    final public function subscribe($event, $callback)
    {
        $this->_initialize();
        if (!isset($this->_events[$event])) syserr();
        $this->_events[$event]->add($callback);
    }

    final public function unsubscribe($event, $callback)
    {
        $this->_initialize();
        if (!isset($this->_events[$event])) syserr();
        $this->_events[$event]->remove($callback);
    }

    final public function raise($event)
    {
        $this->_initialize();
        if (!isset($this->_events[$event])) syserr();
        $arguments = array_slice(func_get_args(), 1);
        $this->_events[$event]->invoke($arguments);
    }

    final public function configure($configuration)
    {
        $configs = $this->configs();
        foreach ($configs as $key => $value)
        {
            $item = is_int($key) ? $value : $key;
            if (isset($configuration[$item]))
            {
                if (is_callable($value))
                    call_user_func($value, $configuration[$item]);
                elseif (is_string($value))
                    $this->$value = $configuration[$item];
                else syserr();
            }
        }
    }

    protected function events()
    {
        return array();
    }

    protected function configs()
    {
        return array();
    }

    private function _initialize()
    {
        if (!$this->_initialized)
        {
            foreach ($this->events() as $event)
                $this->_events[$event] = new CEvent();
            $this->_initialized = true;
        }
    }
}
