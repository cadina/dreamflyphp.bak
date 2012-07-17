<?php

trait TRegExpRoute
{
    protected function regexpMatch($pattern, $match, &$action, &$params)
    {
        $pattern = preg_replace_callback(
            ['/^([^<>]+)$/', '/^([^<>]+)(?=\<)/', '/(?<=\>)([^<>]+)$/', '/(?<=\>)([^<>]+)(?=\<)/'],
            function($m) {return preg_quote($m[1], '/');},
            $pattern);
        $pattern = preg_replace(
            ['/<([^<>:]+)>/', '/<(\w+):([^<>]+)>/'],
            ['(\1)', '(?P<\1>\2)'],
            $pattern);
        if (preg_match('/^' . $pattern . '$/', $match, $matches)) {
            $initParams = (new CArray($params))[array_filter(array_keys($params), 'is_string')];
            $pathParams = (new CArray($matches))[array_filter(array_keys($matches), 'is_string')];
            $rawParams = array_merge($initParams, $pathParams);
            $replaceFunc = function($subject) use($rawParams) {
                if (is_string($subject)) {
                    $subject = str_replace(['\\<', '\\<'], ['<', '>'], $subject);
                    $subject = preg_replace_callback('/\<([^:]+)(:(.*))?\>/', function($matches) use($rawParams) {
                        return empty($matches[1]) ? '' : ($rawParams[$matches[1]] ?: $matches[3]);
                    }, $subject);
                }
                return $subject;
            };
            $action = $replaceFunc($action);
            $params = array_map($replaceFunc, (new CArray($params))[array_filter(array_keys($params), 'is_int')]);
            return true;
        }
        return false;
    }

    protected function regexpGenerate($params)
    {
    }
}