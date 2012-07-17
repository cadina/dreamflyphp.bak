<?php

class CHostRoute extends CWrapRoute
{
    use TRegExpRoute;

    protected $pattern;
    protected $action;
    protected $params;

    public function __construct($pattern, $action, $params)
    {
        $this->pattern = $pattern;
        $this->action = $action;
        $this->params = $params;
    }
    
    public function wrapMatch($request, &$action, &$params)
    {
        $action = $this->action;
        $params = $this->params;
        return $this->regexpMatch($this->pattern, $request->host, $action, $params);
    }

    public function wrapGenerate($params)
    {
        return $this->regexpGenerate($params);
    }
}