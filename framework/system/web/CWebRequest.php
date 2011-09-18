<?php

class CWebRequest extends CModule
{

    public $protocol;

    public $https;

    public $method;

    public $host;

    public $path;

    public $url;
	
	protected function initialize()
	{
	    $this->protocol = $_SERVER['SERVER_PROTOCOL'];
	    $this->https = empty($_SERVER['HTTPS']);
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->host = $_SERVER['HTTP_HOST'];
		$this->path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
        $this->url = ($this->https ? 'https' : 'http') . '://' . $this->host . $this->path;
	}
}
