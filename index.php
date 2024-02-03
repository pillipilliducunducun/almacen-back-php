<?php
//index.php
require __DIR__ . '/vendor/autoload.php';

// Configuración con detalles de error habilitados
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

// Instancia de la aplicación con configuración
$app = new \Slim\App($configuration);

// Acceso al contenedor de dependencias
$container = $app->getContainer();

// Definición del servicio 'db'
$container['db'] = function ($container) {
    return (require __DIR__ . '/src/config/db.php')();
};

// Definición del controlador
$container['ProductController'] = function ($container) {
    // Asegúrate de que la clase ProductController se carga correctamente
    return new \Src\Controllers\ProductController($container->get('db'));
};

// Registro de rutas
require __DIR__ . '/src/routes/web.php';

// Ejecución de la aplicación
$app->run();
