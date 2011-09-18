<?php

final class CRouter extends CModule implements IRouter
{
    protected $routes = array();
    
    public function resolve($request, &$controller, &$action, &$params)
    {
        $url = '';
        foreach ($this->routes as $name => $route)
        {
            if ($name = $route->matchUrl($request->url, $controller, $action, $params))
            {
                return $name;
            }
        }
    }
    
    public function generate($name, $params)
    {
        ;
    }
    
    protected function initialize()
    {
        parent::initialize();
        $this->configure(CConfig::load('route'));
    }
    
    protected function configs()
    {
        return array(
            'routes' => array($this, 'configRoutes'),
            );
    }
    
    protected function configRoutes($routes)
    {
        foreach ($routes as $name => $config)
        {
            $pattern = isset($config['pattern']) ? $config['pattern'] : $config[0];
            $controller = isset($config['controller']) ? $config['controller'] : $config[1];
            $action = isset($config['action']) ? $config['action'] : $config[2];
            $params = isset($config['params']) ? $config['params'] : @$config[3];
            if (empty($params)) $params = array();
            $this->routes[$name] = new CRoute($pattern, $controller, $action, $params);
        }
    }
}

class CRoute extends CBase
{
    protected $pattern;
    protected $controller;
    protected $action;
    protected $params;
    
    public function __construct($pattern, $controller, $action, $params = array())
    {
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
    }
    
    public function matchUrl($url, &$controller, &$action, &$params)
    {
        $pattern = $this->pattern;
        if (preg_match('/^\/\//', $pattern)) $pattern = '<_protocol:[a-zA-Z]+>://' . $pattern;
        else if (preg_match('/^\//', $pattern)) $pattern = '<_protocol:[a-zA-Z]+>://<_host:[a-zA-Z.-]+>' . $pattern;
        $pattern = preg_replace_callback('/^([^<>]+)</', function($matches) { return preg_quote($matches[1], '/').'<'; }, $pattern);
        $pattern = preg_replace_callback('/>([^<>]+)</', function($matches) { return '>'.preg_quote($matches[1], '/').'<'; }, $pattern);
        $pattern = preg_replace_callback('/>([^<>]+)$/', function($matches) { return '<'.preg_quote($matches[1], '/'); }, $pattern);
        $pattern = preg_replace(array('/<([^<>:]+)>/', '/<(\w+):([^<>]+)>/'), array('(\1)', '(?P<\1>\2)'), $pattern);
        if (preg_match('/^' . $pattern . '$/', $url, $matches))
        {
            $patterns = array('/\\\</', '/\\\>/');
            $replacements = array('<', '>');
            foreach ($matches as $name => $value)
            {
                if (is_string($name))
                {
                    $patterns[] = '/<' . $name . '>/';
                    $replacements[] = $value;
                }
            }
            $controller = $this->controller;
            $controller = preg_replace($patterns, $replacements, $controller);
            $action = $this->action;
            $action = preg_replace($patterns, $replacements, $action);
            $params = array();
            foreach ($this->params as $name => $param)
            {
                $params[$name] = preg_replace($patterns, $replacements, $param);
            }
            return true;
        }
        return false;
    }
}

