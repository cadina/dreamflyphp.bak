<?php

class CHttpResult extends CActionResult
{
    protected $status;
    protected $header;
    protected $body;

    public function __construct($status = null, $header = null, $body = null)
    {
        $this->status = $status;
        $this->header = $header;
        $this->body = $body;
    }

    public function execute($context)
    {
        if (isset($this->status)) {
            header("HTTP/1.1 {$this->status}");
        }
        if (isset($this->header)) {
            if (is_array($this->header)) {
                foreach ($this->header as $header) {
                    header($header);
                }
            }
            else {
                header($this->header);
            }
        }
        if (isset($this->body)) {
            if ($this->body instanceof CActionResult) {
                $this->body->execute($context);
            }
            else {
                echo $this->body;
            }
        }
    }
}