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
        $path = $request->path;
        $pattern = $this->pattern;
        $pattern = preg_replace_callback(
            ['/^([^<>]+)$/', '/^([^<>]+)(?=\<)/', '/(?<=\>)([^<>]+)$/', '/(?<=\>)([^<>]+)(?=\<)/'],
            function($m) {return preg_quote($m[1], '/');},
            $pattern);
        $pattern = preg_replace(
            ['/<([^<>:]+)>/', '/<(\w+):([^<>]+)>/'],
            ['(\1)', '(?P<\1>\2)'],
            $pattern);
        if (preg_match('/^' . $pattern . '$/', $path, $matches)) {
            $pathParams = (new CArray($matches))[array_filter(array_keys($matches), 'is_string')];
            $getParams = $request->get->getArray();
            $rawParams = array_merge($getParams, $pathParams);
            $replaceFunc = function($subject) use($rawParams) {
                if (is_string($subject)) {
                    $subject = str_replace(['\\<', '\\<'], ['<', '>'], $subject);
                    $subject = preg_replace_callback('/\<([^:]+)(:(.*))?\>/', function($matches) use($rawParams) {
                        return empty($matches[1]) ? '' : ($rawParams[$matches[1]] ?: $matches[3]);
                    }, $subject);
                }
                return $subject;
            };
            $action = $this->action;
            $action = $replaceFunc($action);
            $params = array_map($replaceFunc, $this->params);
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

