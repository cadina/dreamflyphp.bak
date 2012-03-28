<?php

class CRegExpRoute implements IRoute
{
    protected $pattern;
    protected $action;
    protected $params;
    protected $generator;
    
    public function __construct($pattern, $action, $params = [], $generator = null)
    {
        $this->pattern = $pattern;
        $this->action = $action;
        $this->params = $params;
        $this->generator = $generator;
    }
    
    public function match($request, &$action, &$params)
    {
        $path = $request->path_info;
        $pattern = $this->pattern;
        $pattern = preg_replace_callback('/^([^<>]+)$/', function($matches) {
            return preg_quote($matches[1], '/');
        }, $pattern);
        $pattern = preg_replace_callback('/^([^<>]+)</', function($matches) {
            return preg_quote($matches[1], '/').'<';
        }, $pattern);
        $pattern = preg_replace_callback('/>([^<>]+)</', function($matches) {
            return '>'.preg_quote($matches[1], '/').'<';
        }, $pattern);
        $pattern = preg_replace_callback('/>([^<>]+)$/', function($matches) {
            return '>'.preg_quote($matches[1], '/');
        }, $pattern);
        $pattern = preg_replace(
            ['/<([^<>:]+)>/', '/<(\w+):([^<>]+)>/'],
            ['(\1)', '(?P<\1>\2)'],
            $pattern);
        if (preg_match('/^' . $pattern . '$/', $path, $matches)) {
            $patterns = array('/\\\</', '/\\\>/');
            $replacements = array('<', '>');
            foreach ($request->get as $name => $value) {
                $patterns[$name] = '/<' . $name . '>/';
                $replacements[$name] = $value;
            }
            foreach ($matches as $name => $value) {
                if (is_string($name)) {
                    $patterns[$name] = '/<' . $name . '>/';
                    $replacements[$name] = $value;
                }
            }
            $patterns[] = '/<.*>/';
            $replacements[] = '';
            $action = $this->action;
            $action = preg_replace($patterns, $replacements, $action);
            $params = array();
            foreach ($this->params as $name => $param) {
                $params[$name] = preg_replace($patterns, $replacements, $param);
            }
            return true;
        }
        return false;
    }

    public function generate($params)
    {
        if (isset($this->generator)) {
            call_user_func($this->generator, $params);
        }
        else {
            return '';
        }
    }
}

