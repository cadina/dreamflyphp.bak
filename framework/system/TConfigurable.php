<?php

trait TConfigurable
{

    protected function configs()
    {
        return [];
    }


    final public function configure($configuration)
    {
        $configs = $this->configs();
        foreach ($configs as $key => $value) {
            $item = is_int($key) ? $value : $key;
            if (isset($configuration[$item])) {
                if (is_callable($value)) {
                    call_user_func($value, $configuration[$item]);
                }
                elseif (is_string($value)) {
                    $this->$value = $configuration[$item];
                }
            }
        }
    }

}