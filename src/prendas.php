<?php
namespace Controladores;
class prendas implements \Library\Controllers\Controller
{

    public function get($post,$get,&$session)
    {
        require_once('../src/BaseDeDatos.php');
        if(!array_key_exists('carrito',$session))
        {
            $session['carrito']=array();
        }
        if(isset($session['logueado'])and $session['logueado'])
        {
            $strDatosEspecificos="<h2>PRENDAS</h2><ol>";
            foreach($productos as $prenda=>$tipo)
            {
                $strDatosEspecificos.='<li>'.$prenda.'<ul>';
                foreach($tipo as $producto=>$descripcion)
                {
                    $templateEspecifico=new \Library\templateEngine\TemplateEngine("../templates/contenidoEspecificoPrendas.html","{{","}}");
                    $templateEspecifico->addVariable('producto',$producto);
                    $templateEspecifico->addVariable('prenda',$prenda);
                    $strDatosEspecificos.=$templateEspecifico->render();
                }
                $strDatosEspecificos.='</ul></li>';
            }
            $strDatosEspecificos.='</ol><br>';
            $templateProductos= new \Library\templateEngine\TemplateEngine("../templates/mainTemplate.html","{{","}}");
            $templateProductos->addVariable('titulo','LISTA DE PRODUCTOS');
            $templateProductos->addVariable('contenido',$strDatosEspecificos);
            if(!empty($session['carrito']))
            {
                $templateProductos->addVariable('carrito','<a href="./VerCarrito">Ver Carrito</a><br>');       
            }
            return $templateProductos->render();
        }
        else
        {
            $te2=new \Library\templateEngine\TemplateEngine("../templates/noEstasLogueado.html","{{","}}");
            return $te2->render();
        }
    }
    public function post($post,$get,&$session)
    {
        require_once('../src/BaseDeDatos.php');
        if (isset($session['carrito'][$post['prenda']][$post['producto']]))
                {
                    $session['carrito'][$post['prenda']][$post['producto']]['cantidad']+=1;
                }
                else
                {
                    $session['carrito'][$post['prenda']][$post['producto']]=$productos[$post['prenda']][$post['producto']];
                    $session['carrito'][$post['prenda']][$post['producto']]['cantidad']=1;
                }
                header('Location: ./prendas');
    }
}