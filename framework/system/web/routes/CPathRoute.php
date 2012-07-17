<?php

class CPathRoute implements IRoute
{
    use TRegExpRoute;

    protected $pattern;
    protected $action;
    protected $params;
    
    public function __construct($pattern, $action, $params = [])
    {
        $this->pattern = $pattern;
        $this->action = $action;
        $this->params = is_array($params) ? $params : [$params];
    }

    public function match($request, &$action, &$params)
    {
        $action = $this->action;
        $params = $this->params;
        return $this->regexpMatch($this->pattern, $request->path, $action, $params);
    }
    
    public function generate($params)
    {
        return $this->regexpGenerate($params);
    }

}

