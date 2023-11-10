<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->get('dashboard', 'DashboardController::index',['filter' => 'auth']);
//$routes->get('tickets', 'TicketController::index',['filter' => 'auth']);
//$routes->get('responses', 'ResponseController::index',['filter' => 'auth']);

//$routes->post('authors/list', 'AuthorController::list', ['filter' => 'groupfilter:admin']);
$routes->get('profiles/list', 'ProfileController::list', ['filter' => 'groupfilter:admin']);
$routes->get('office/list', 'OfficeController::list', ['filter' => 'groupfilter:admin']);
$routes->get('status/list', 'StatusController::list', ['filter' => 'groupfilter:admin']);
$routes->get('condition/list', 'ConditionController::list', ['filter' => 'groupfilter:admin']);
$routes->get('ticket/list', 'TicketController::list', ['filter' => 'auth']);
$routes->get('response/list', 'ResponseController::list', ['filter' => 'auth']);
//$routes->post('posts/list', 'PostController::list');


$routes->resource('authors', ['controller' => 'AuthorController','filter' => 'groupfilter:admin', 'except' => ['new', 'edit']]);
//$routes->resource('posts', ['controller' => 'PostController','filter' => 'auth', 'except' => ['new', 'edit']]);
$routes->resource('profiles', ['controller' => 'ProfileController','filter' => 'groupfilter:admin']);
$routes->resource('users', ['controller' => 'UsersController','filter' => 'groupfilter:admin']);
$routes->resource('condition', ['controller' => 'ConditionController','filter' => 'groupfilter:admin']);
$routes->resource('status', ['controller' => 'StatusController','filter' => 'groupfilter:admin']);
$routes->resource('office', ['controller' => 'OfficeController','filter' => 'groupfilter:admin']);
$routes->resource('ticket', ['controller' => 'TicketController','filter' => 'auth']);
$routes->resource('response', ['controller' => 'ResponseController','filter' => 'auth']);


service('auth')->routes($routes);


