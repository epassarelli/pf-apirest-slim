<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['db']['host']   = "localhost";
$config['db']['user']   = "root";
$config['db']['pass']   = "";
$config['db']['dbname'] = "lalo0471_mfa";

//Establezco el seteo de configuraciones
$app = new \Slim\App(["settings" => $config]);

//Obtengo el contenedor de inyección de dependencia
$container = $app->getContainer();


// Agregar una conexión de base de datos
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// Método que obtiene todos los interpretes
$app->get('/interpretes', function (Request $request, Response $response) {
    $mapper = new InterpreteMapper($this->db);
    $interpretes = $mapper->getInterpretes();

    //$response = $this->view->render($response, "tickets.phtml", ["tickets" => $tickets, "router" => $this->router]);
    //return $response;
    return $response->withHeader(
        'Content-Type',
        'application/json'
    );
});




$app->run();