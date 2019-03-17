<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return 'api test';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
