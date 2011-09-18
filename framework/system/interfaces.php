<?php


interface ISingleton
{
    static function getInstance();
}

interface IEventSource
{
    function subscribe($event, $callback);
    function unsubscribe($event, $callback);
    function raise($event);
}

interface IView
{
    function content();
}

interface IViewEngine
{
    function render($view, $output = true);
}


/**
 * IEvent
 * 
 * @author cadina
 */
interface IEvent
{
    function add($callback);
    function remove($callback);
    function invoke();
}


/**
 * IApplication
 * 
 * @author cadina
 */
interface IApplication
{
    function run();
}


/**
 * IExecutable
 * 
 * @author cadina
 */
interface IExecutable
{
    function execute($context);
}


/**
 * IConfigurable
 * 
 * @author cadina
 */
interface IConfigurable
{
    function configure($configuration);
}


/**
 * IRouter
 * 
 * @author cadina
 */
interface IRouter
{
    function resolve($request, &$controller, &$action, &$params);
    function generate($route, $params);
}



















