<?php
namespace Controladores;
class LogInCheck implements \Library\Controllers\Controller
{

    public function get($post,$get,&$session)
    {
        if ($session['logueado'] and isset($session['logueado']))
        {
            return $this->pagina->get($post,$get,$session);
        }
        else
        {
            $this->pagina=new \Controladores\noEstasLogueado();
            return $this->pagina->get($post,$get,$session);
        }
    }
    public function post($post,$get,&$session)
    {
        if ($session['logueado'] and isset($session['logueado']))
        {
            return $this->pagina->post($post,$get,$session);
        }
        else
        {
            $this->pagina=new \Controladores\noEstasLogueado();
            return $this->pagina->get($post,$get,$session);
        }
    }
}