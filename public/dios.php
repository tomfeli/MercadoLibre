<?php
include_once('../vendor/autoload.php');

/*echo "<pre>";
print_r($_SERVER);
echo "</pre>";*/

session_start();
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteContext;
require __DIR__ . '/../vendor/autoload.php';
$app = AppFactory::create();



// hoy me falto esta linea :( , es magia negra, no se preocupen
$app->addRoutingMiddleware();
$logInCheck=function(Request $request, RequestHandler $next) {

    if ( isset($session['logueado']) && $session['logueado'] )
    {
        $response = new \Slim\Psr7\Response();
        $noEstasLogueado= new \Controladores\noEstasLogueado();
        $response->getBody()->write($noEstasLogueado->get($_POST,$_GET,$_SESSION));
        return $response;
    }
    else
    {
        return $next->handle($request);
    }
};
$app->get('/', function (Request $request, Response $response, array $args) {

    session_destroy();
    session_start();
    $te=new \Library\templateEngine\TemplateEngine("../templates/indexTemplate.html","{{","}}");
    $te->addVariable("ruta",'./index');
    $response->getBody()->write($te->render());
    return $response;
});
$app->get('/index', function (Request $request, Response $response, array $args) {

    session_destroy();
    session_start();
    $te=new \Library\templateEngine\TemplateEngine("../templates/indexTemplate.html","{{","}}");
    $te->addVariable("ruta",'./index');
    $response->getBody()->write($te->render());
    return $response;
});
$app->post('/index', function (Request $request, Response $response, array $args) {

    require_once("../src/BaseDeDatos.php");
    $esUsuario=false;
    foreach($listaDeUsuarios as $clave=>$user)
    {
        if($clave==$_POST['contrasenia'] && $user==$_POST['usuario'])
        {
            $esUsuario=true;
        }    
    }
    if($esUsuario)
    {
        $session['logueado']=true;
        $te=new \Library\templateEngine\TemplateEngine("../templates/EstasLogueado.html","{{","}}");
        $response->getBody()->write($te->render());
        return $response;
    }
    else
    {
        $te2=new \Library\templateEngine\TemplateEngine("../templates/noEstasLogueado.html","{{","}}");
        $response->getBody()->write($te2->render());
        return $response;
    }
});
$app->group('', function (\Slim\Routing\RouteCollectorProxy $group){
    $group->post('/prendas', function (Request $request, Response $response, array $args) {

        print_r($_SESSION);
        require_once('../src/BaseDeDatos.php');
        if (isset($_SESSION['carrito'][$_POST['prenda']][$_POST['producto']]))
                {
                    $_SESSION['carrito'][$_POST['prenda']][$_POST['producto']]['cantidad']+=1;
                }
                else
                {
                    $_SESSION['carrito'][$_POST['prenda']][$_POST['producto']]=$productos[$_POST['prenda']][$_POST['producto']];
                    $_SESSION['carrito'][$_POST['prenda']][$_POST['producto']]['cantidad']=1;
                }
                $response=$response->withStatus(302);
                $response=$response->withHeader('Location','/prendas');
                return $response;
    });
$group->get('/prendas', function (Request $request, Response $response, array $args) {    
    require_once('../src/BaseDeDatos.php');
        if(!array_key_exists('carrito',$_SESSION))
        {
            $_SESSION['carrito']=array();
        }
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
            if(!empty($_SESSION['carrito']))
            {
                $templateProductos->addVariable('carrito','<a href="./VerCarrito">Ver Carrito</a><br>');       
            }
            $response->getBody()->write($templateProductos->render());
            return $response;
});
$group->get('/Ver', function (Request $request, Response $response, array $args){

    require_once('../src/BaseDeDatos.php');            
        
    $templateEspecifico=new \Library\templateEngine\TemplateEngine('../templates/contenidoEspecificoVer.html','{{','}}');
    $templateEspecifico->addVariable('precio',strval($productos[$_GET['prenda']][$_GET['producto']]['precio']));
    $templateEspecifico->addVariable('talle',$productos[$_GET['prenda']][$_GET['producto']]['talle']);
    $templateEspecifico->addVariable('prenda',$_GET['prenda']);
    $templateEspecifico->addVariable('producto',$_GET['producto']);
    $strEspecifico=$templateEspecifico->render();
    if(!empty($_SESSION['carrito']))
    {        
    $strEspecifico.='<a href="./VerCarrito">Ver Carrito</a><br>';
    }
    $templateProductos= new \Library\templateEngine\TemplateEngine("../templates/mainTemplate.html","{{","}}");
    $templateProductos->addVariable('titulo',$_GET['producto']);
    $templateProductos->addVariable('contenido',$strEspecifico);
    $response->getBody()->write($templateProductos->render());
    return $response;
});
$group->get('/VerCarrito', function (Request $request, Response $response, array $args) {

    require_once('../src/BaseDeDatos.php');
        
    $strEspecifico='';
    foreach($_SESSION['carrito'] as $prenda=>$productos)
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
    $response->getBody()->write($teVerCarrito->render());
    return $response;
});
$group->post('/VerCarrito', function (Request $request, Response $response, array $args){    

    $_SESSION['carrito'][$_POST['borrar']][$_POST['borrar2']]['cantidad']--;
    if($_SESSION['carrito'][$_POST['borrar']][$_POST['borrar2']]['cantidad']==0)
    {
        unset($_SESSION['carrito'][$_POST['borrar']][$_POST['borrar2']]);
    }
    $response=$response->withStatus(302);
    $response=$response->withHeader('Location','/VerCarrito');
    return $response;
});
})->add($logInCheck);

$app->run();
/*
$router= new Library\Router\Router();
$router->addRout("/index",new Controladores\index());
$router->addRout("/prendas",new Controladores\LogInCheck(
                                new Controladores\prendas()));
$router->addRout("/VerCarrito",new Controladores\LogInCheck(
                                new Controladores\VerCarrito()));
$router->addRout("/Ver",new Controladores\LogInCheck(
                                new Controladores\Ver()));
if($_SERVER['SCRIPT_NAME']=='/')
{

    $pagina=new Controladores\index();
    print_r($pagina);
    echo $pagina->get($_POST,$_GET,$_SESSION);
}
else
{
    if ($_SERVER['REQUEST_METHOD']=='POST')
    {
        $pagina=$router->matchRout($_SERVER['SCRIPT_NAME']);
        if($pagina!=null)
        {
            echo $pagina->post($_POST,$_GET,$_SESSION);
        }
    }
    else
    {    
        $pagina=$router->matchRout($_SERVER['SCRIPT_NAME']);
        if($pagina!=null)
        {
            echo $pagina->get($_POST,$_GET,$_SESSION);
        }

    }
}
*/