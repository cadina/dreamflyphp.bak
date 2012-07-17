<?php

class CWebRequest extends CComponent
{

    public $protocol;
    public $https;
    public $method;
    public $host;
    public $path;
    public $url;
    public $get;
    public $post;
	
	public function initialize()
	{
	    $this->protocol = $_SERVER['SERVER_PROTOCOL'];
	    $this->https = !empty($_SERVER['HTTPS']);
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->host = $_SERVER['HTTP_HOST'];
		$this->path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
        $this->url = $this->https ? 'https://' : 'http://' . $this->host . $_SERVER['REQUEST_URI'];
        $this->get = new CArray($_GET);
        $this->post = new CArray($_POST);
	}

    public function getInput()
    {
        return file_get_contents('php://input');
    }
}
