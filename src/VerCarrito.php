<?php
namespace Controladores;
class VerCarrito implements \Library\Controllers\Controller
{

    public function get($post,$get,&$session)
    {
        require_once('../src/BaseDeDatos.php');
        
        $strEspecifico='';
        foreach($session['carrito'] as $prenda=>$productos)
        {
            foreach($productos as $producto=>$propiedades)
            {
                $strPropiedades="";
                foreach($propiedades as $propiedad=>$valor)
                {
                   $tepropiedades=new \Library\templateEngine\TemplateEngine("../templates/propiedades.html","{{","}}");
                   $tepropiedades->addVariable("propiedad",$propiedad);
                   $tepropiedades->addVariable("valor",strval($valor));
                   $strPropiedades.=$tepropiedades->render();
                }
                $teEspecifico=new \Library\templateEngine\TemplateEngine("../templates/contenidoEspecificoVerCarrito.html","{{","}}");
                $teEspecifico->addVariable("producto",$producto);
                $teEspecifico->addVariable("prenda",$prenda);
                $teEspecifico->addVariable("contenido",$strPropiedades);
                $strEspecifico.=$teEspecifico->render();
            }
        }
        $teVerCarrito=new \Library\templateEngine\TemplateEngine("../templates/mainTemplate.html","{{","}}");
        $teVerCarrito->addVariable("titulo","CARRITO");
        $teVerCarrito->addVariable("contenido",$strEspecifico);
        $teVerCarrito->addVariable("carrito",'<br><a href="./prendas">VOLVER</a><br>');
        return $teVerCarrito->render();
        }

    public function post($post,$get,&$session)
    {
            $session['carrito'][$post['borrar']][$post['borrar2']]['cantidad']--;
            if($session['carrito'][$post['borrar']][$post['borrar2']]['cantidad']==0)
            {
                unset($session['carrito'][$post['borrar']][$post['borrar2']]);
            }
            header('Location: ./VerCarrito');
    }
}