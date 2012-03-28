<?php

class TestAction extends CAction
{
    public function get($id)
    {
        echo $id;
    }
}