<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Src\Controllers\ProductController;

// Obtén el contenedor de Slim
$container = $app->getContainer();

// Registra el controlador en el contenedor
$container[ProductController::class] = function ($container) {
    return new ProductController($container->get('db'));
};

// Definición de rutas para los productos
$app->group('/products', function () use ($app) {
    // Obtener todos los productos
    $app->get('', ProductController::class . ':getAllProducts');

    // Obtener un producto por ID
    $app->get('/{id:[0-9]+}', ProductController::class . ':getProductById');

    // Crear un nuevo producto
    $app->post('', ProductController::class . ':createProduct');

    // Actualizar un producto existente
    $app->put('/{id:[0-9]+}', ProductController::class . ':updateProduct');

    // Eliminar un productoS
    $app->delete('/{id:[0-9]+}', ProductController::class . ':deleteProduct');
});

