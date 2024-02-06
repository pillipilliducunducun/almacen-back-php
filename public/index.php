<?php
//index.php

require __DIR__ . '/../vendor/autoload.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new \Slim\App($configuration);
$container = $app->getContainer();

$container['db'] = function ($container) {
    return (require __DIR__ . '/../src/config/db.php')();
};

require __DIR__ . '/../src/routes/web.php';


try {
    // Ejecución de la aplicación
    $app->run();
} catch (\Exception $e) {
    // Aquí puedes manejar la excepción como prefieras
    // Por ejemplo, enviando una respuesta JSON con el error
    $response = $app->getContainer()->get('response');
    return $response->withStatus(500)
                    ->withHeader('Content-Type', 'application/json')
                    ->write(json_encode(['error' => $e->getMessage()]));
}
