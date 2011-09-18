<?php


class HomeController extends CController
{

    public function indexAction()
    {
        echo 'a';
    }

    public function piggyAction()
    {
        echo 'b';
    }

    public function postAction($id)
    {
        echo $id;
    }

}