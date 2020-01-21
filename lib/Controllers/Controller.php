<?php
namespace Library\Controllers;

interface Controller
{
    public function get($post,$get,&$session);
    public function post($post,$get,&$session);
}