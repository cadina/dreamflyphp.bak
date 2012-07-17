<?php

class CJsonResult extends CActionResult
{
    use TConfigurable;

    protected $data;
    protected $options;
    protected $charset = 'UTF-8';

    public function __construct($data, $options = 0)
    {
        $this->data = $data;
        $this->options = $options;
    }

    public function execute($context)
    {
        $this->configure($context->application->loadConfig('json'));

        header("Content-Type: application/json;charset={$this->charset}");
        echo json_encode($this->data, $this->options);
    }

    protected function configs()
    {
        return [
            'encode_options' => function($options) {
                $this->options |= $options;
            },
            'encode_charset',
        ];
    }
}