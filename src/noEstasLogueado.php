<?php
namespace Controladores;
class noEstasLogueado implements \Library\Controllers\Controller
{
    public function get($post,$get,&$session)
    {
    $te2=new \Library\templateEngine\TemplateEngine("../templates/noEstasLogueado.html","{{","}}");
    return $te2->render();
    }
    public function post($post,$get,&$session)
    {
        
    }
}