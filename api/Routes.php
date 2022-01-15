<?php

// Require composer autoloader
use Bramus\Router\Router;
use Infinitops\Inventory\Controllers\ItemsController;
use Infinitops\Inventory\Controllers\BrandsController;
use Infinitops\Inventory\Controllers\CategoriesController;


require __DIR__ . '/vendor/autoload.php';

$router = new Router();

// Custom 404 Handler
$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    $notFound = file_get_contents("assets/404.html");
    echo $notFound;
});
$router->setNamespace('\Infinitops\Inventory\Controllers');

$router->get('/accessRoute', function () {
    echo 'Welcome';
});

// $router->get('/brands', "BrandsController@getBrands");

$router->get('/brands', function () {
    $controller = new BrandsController();
    $controller->getBrands();
});

$router->get('/brand/{id}', function ($id) {
    $controller = new BrandsController();
    $controller->getBrand($id);
});

$router->post('/brand', function () {
    $controller = new BrandsController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->createBrand($data);
});

$router->post('/brand/{id}', function ($id) {
    $controller = new BrandsController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->updateBrand($data, $id);
});

$router->get('/categories', function () {
    $controller = new CategoriesController();
    $controller->getCategories();
});

$router->get('/category/{id}', function($id) {
    $controller = new CategoriesController();
    $controller->getCategory($id);
});

$router->post('/category', function () {
    $controller = new CategoriesController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->createCategory($data);
});

$router->post('/category/{id}', function ($id) {
    $controller = new CategoriesController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->updateCategory($data, $id);
});

$router->get('/items', function () {
    $controller = new ItemsController();
    $controller->getItems();
});

$router->get('/item/{id}', function ($id) {
    $controller = new ItemsController();
    $controller->getItem($id);
});

$router->post('/item', function () {
    $controller = new ItemsController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->createItem($data);
});

$router->post('/item/{id}', function ($id) {
    $controller = new ItemsController();
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->updateItem($data, $id);
});

// Thunderbirds are go!
$router->run();
