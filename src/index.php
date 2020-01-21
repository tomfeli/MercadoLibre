<?php
namespace Controladores;
class index implements \Library\Controllers\Controller
{

    public function get($post,$get,&$session)
    {
        session_destroy();
        session_start();
        $te=new \Library\templateEngine\TemplateEngine("../templates/indexTemplate.html","{{","}}");
        $te->addVariable("ruta",'./index');
        return $te->render();
    }
    public function post($post,$get,&$session)
    {
        require_once("../src/BaseDeDatos.php");

        $esUsuario=false;
        foreach($listaDeUsuarios as $clave=>$user)
        {
            if($clave==$post['contrasenia'] && $user==$post['usuario'])
            {
                $esUsuario=true;
            }    
        }
        if($esUsuario)
        {
            $session['logueado']=true;
            $te=new \Library\templateEngine\TemplateEngine("../templates/EstasLogueado.html","{{","}}");
            return $te->render();
        }
        else
        {
            $te2=new \Library\templateEngine\TemplateEngine("../templates/noEstasLogueado.html","{{","}}");
            return $te2->render();
        }
    }
}