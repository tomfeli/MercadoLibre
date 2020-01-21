<?php
namespace Controladores;
class Ver implements \Library\Controllers\Controller
{

    public function get($post,$get,&$session)
    {
        require_once('../src/BaseDeDatos.php');            
        
            $templateEspecifico=new \Library\templateEngine\TemplateEngine('../templates/contenidoEspecificoVer.html','{{','}}');
            $templateEspecifico->addVariable('precio',strval($productos[$get['prenda']][$get['producto']]['precio']));
            $templateEspecifico->addVariable('talle',$productos[$get['prenda']][$get['producto']]['talle']);
            $templateEspecifico->addVariable('prenda',$get['prenda']);
            $templateEspecifico->addVariable('producto',$get['producto']);
            $strEspecifico=$templateEspecifico->render();
            if(!empty($session['carrito']))
            {        
            $strEspecifico.='<a href="./VerCarrito">Ver Carrito</a><br>';
            }
            $templateProductos= new \Library\templateEngine\TemplateEngine("../templates/mainTemplate.html","{{","}}");
            $templateProductos->addVariable('titulo',$get['producto']);
            $templateProductos->addVariable('contenido',$strEspecifico);
            return $templateProductos->render();
    }

    
    public function post($post,$get,&$session)
    {
        
    }
}