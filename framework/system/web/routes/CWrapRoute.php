<?php

abstract class CWrapRoute implements IRoute
{
    private $_subroutes = [];
    
    public function __construct($subroutes)
    {
        $this->_subroutes = is_array($subroutes) ? $subroute : [$subroute];
    }

    public function match($request, &$action, &$params)
    {
        $wrapAction = '';
        $wrapParams = [];
        if ($this->wrapMatch($request, $wrapAction, $wrapParams)) {
            foreach ($this->_subroutes as $subroute) {
                $subAction = null;
                $subParams = null;
                if ($subroute->match($request, $subAction, $subParams)) {
                    $action = $wrapAction . $subAction;
                    $params = $wrapParams + $subParams;
                    return true;
                }
            }
        }
        return false;
    }

    public function generate($params)
    {
    }

    abstract function wrapMatch($request, &$action, &$params);
    abstract function wrapGenerate($params);
}