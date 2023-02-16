<?php
//https://github.com/codeguy/Slim-Views

session_start();
require 'vendor/autoload.php';
$app = new \Slim\Slim(array(
	//para cargar el twig
    'view' => new \Slim\Views\Twig()
));

// configuraciones de slim
$app->config(array(
	'debug' => true,
	'templates.path' => 'views'
));

//configuraiciones de twig
$view = $app->view();
$view->parserOptions = array(
    'debug' => true,
    'cache' => dirname(__FILE__) . '/cache'
);

//carga de extenciones de twig ---urlFor siteUrl baseUrl
$view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
);


//Hooks
$app->hook('slim.before', function() use($app){
	$app->view()->appendData(array('baseUrl' => WEB));
});

define('SPECIALCONSTANT', true);
define('WEB', 'http://elantiguo');//comment for git push
//define('WEB', 'http://www.elantiguoadm.com');//uncomment for git push

//Conexion
require_once 'conexion.php';
require_once 'controllers/index.php';
require_once 'controllers/login.php';
require_once 'controllers/user.php';
require_once 'controllers/dashboard.php';
require_once 'controllers/stock.php';
require_once 'controllers/inventario.php';
require_once 'controllers/empleado.php';
require_once 'controllers/proveedor.php';
require_once 'controllers/pasivo.php';
require_once 'controllers/detallePasivo.php';
require_once 'controllers/categoria.php';
require_once 'controllers/producto.php';
require_once 'controllers/detalleProducto.php';
require_once 'controllers/manoObra.php';
require_once 'controllers/pedidos.php';
require_once 'controllers/clientes.php';
require_once 'controllers/detallePedido.php';
require_once 'controllers/cobros.php';
require_once 'controllers/deudas.php';
require_once 'controllers/activo.php';
require_once 'controllers/detalleActivo.php';
require_once 'controllers/sueldos.php';
require_once 'controllers/presupuesto.php';
require_once 'controllers/detallePresupuesto.php';

/*Redireccion 404
$app->notFound(function() use($app) {
	$app->render('404.twig');
});*/

$app->run();

?>