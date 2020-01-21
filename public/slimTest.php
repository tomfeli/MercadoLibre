<?php

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


$app->add(function (Request $request, RequestHandler $handler) {
    
    // Para pensar, porque creamos un response nuevo???
    $response = new \Slim\Psr7\Response();
    
    // obtenemos el resultado de ejecutar el controller interno
    $content = (string) $handler->handle($request)->getBody();

    $response->getBody()->write("<html><body><h1>Aplicacion de carrito</h1>$content</body></html>");
    
    return $response;
});


$productos = array(
    '01' => array('id'=> '01', 'name'=>'nombre 01', 'price'=>'100'),
    '02' => array('id'=> '02', 'name'=>'nombre 02', 'price'=>'200'),
    '03' => array('id'=> '03', 'name'=>'nombre 03', 'price'=>'300'),
    '04' => array('id'=> '04', 'name'=>'nombre 04', 'price'=>'400'),
    '05' => array('id'=> '05', 'name'=>'nombre 05', 'price'=>'500'),
);


$middlewareAgregarVariableEdad = function (Request $request, RequestHandler $handler) {

    $old = $request->getAttribute("edad", 0)+1;
    $request = $request->withAttribute("edad", $old);

    $response = $handler->handle($request);

    $response->getBody()->write("<br>terminando " . $old);

    return $response;
};


function mostrarLindo(Array $arr) {
    return "<br><pre>".print_r($arr, true)."</pre>";
}


$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("default sin nada");
    return $response;
});


$app->get("/ver", function(Request $request, Response $response, array $args){
    if (empty($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }
    $response->getBody()->write("Mostrando: " . mostrarLindo($_SESSION['carrito']));
    return $response;
});


$app->group('/producto/{productId}', function (\Slim\Routing\RouteCollectorProxy $group) use ($middlewareAgregarVariableEdad) {
    
    $group->get('/mostrar', function (Request $request, Response $response, array $args) {
        $producto = $request->getAttribute("producto");
        $response->getBody()->write("Mostrando: ".mostrarLindo($producto));
        return $response;
    });

    $group->get('/agregar', function (Request $request, Response $response, array $args) {
        $producto = $request->getAttribute("producto");
        
        if (empty($_SESSION['carrito'][$producto['id']])) {
            $_SESSION['carrito'][$producto['id']] = 0;
        }
        $_SESSION['carrito'][$producto['id']] +=1;

        $response->getBody()->write("Agregamos: ".$producto['name']);
        return $response;
    });
    
    $group->get('/borrar', function (Request $request, Response $response, array $args) {
        $producto = $request->getAttribute("producto");
    
        $_SESSION['carrito'][$producto['id']] -= 1;

        $response->getBody()->write("Borrando ".mostrarLindo($producto));
        return $response;
    })->add($middlewareAgregarVariableEdad);

})->add(function(Request $request, RequestHandler $handle) use ($productos) {
    
    // estas 3 lineas se encargan de sacar la información
    // de la url que esta dentro del request que nos mandan
    // (hoy fallo porque me falto la linea que esta arriba)
    $routeContext = \Slim\Routing\RouteContext::fromRequest($request);
    $route = $routeContext->getRoute();
    $productId = $route->getArgument('productId');

    if (empty($productos[$productId])) {
        // Para pensar, por que tenemos que crear un nuevo response?
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write("No existe ese producto, por favor revise mejor el link");
        return $response;
    }
    
    $request = $request->withAttribute('producto', $productos[$productId]);

    return $handle->handle($request);
});


$app->run();


/**
 * Conceptos:
 * 
 *      Decoradores: Tipos que se ponen en el medio
 *      Middlewares: Son cebollas
 *      Controllers: Bolsa de gatos
 *      Clases y funciones: Bolsas de gatos mas grandes
 * 
 *      Hice un "pseudo template" con el middleware que agrega
 *      el HTML central de la pagina pero el contenido central
 *      de cada sección la genera el controller, fijarse como
 *      funciona.
 * 
 * 
 *      Visitar en el browser:
 * 
 *      # Mostrar el carrito
 *      /ver
 * 
 *      # Mostrar el producto 03
 *      /producto/03/mostrar
 * 
 *      # Agregar al carrito el producto 05
 *      /producto/05/agregar
 * 
 *      # Borrar el producto 01 del carrito
 *      /producto/01/borrar
 * 
 * 
 * Perdón por el fallo del ejemplo a ultimo momento.
 * Gracias por tanto, perdón por tan poco.
 * 
 * Buen finde gente.
 * 
 */