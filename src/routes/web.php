<?php
// web.php

use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/products', function () {

    $this->get('', function (Request $request, Response $response) {
        $db = $this->get('db');
        $query = 'SELECT * FROM products';
        try {
            $stmt = $db->query($query);
            $products = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $response->withJson($products);
        } catch (\PDOException $e) {
            return $response->withJson(['message' => $e->getMessage()], 500);
        }
    });

    $this->get('/{id:[0-9]+}', function (Request $request, Response $response, $args) {
        $db = $this->get('db');
        $query = 'SELECT * FROM products WHERE id = :id';
        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $args['id']);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_OBJ);
            if ($product) {
                return $response->withJson($product);
            } else {
                return $response->withJson(['message' => 'Product not found'], 404);
            }
        } catch (\PDOException $e) {
            return $response->withJson(['message' => $e->getMessage()], 500);
        }
    });

    $this->post('', function (Request $request, Response $response) {
        $db = $this->get('db');
        $body = $request->getParsedBody();
        $query = 'INSERT INTO products (codigo_barra, nombre, valor) VALUES (:codigo_barra, :nombre, :valor)';
        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':codigo_barra', $body['codigo_barra']);
            $stmt->bindParam(':nombre', $body['nombre']);
            $stmt->bindParam(':valor', $body['valor']);
            $stmt->execute();
            return $response->withJson(['message' => 'Inserción exitosa'], 201);
        } catch (\PDOException $e) {
            return $response->withJson(['message' => $e->getMessage()], 400);
        }
    });

    $this->put('/{id:[0-9]+}', function (Request $request, Response $response, $args) {
        $db = $this->get('db');
        $body = $request->getParsedBody();
        $query = 'UPDATE products SET codigo_barra = :codigo_barra, nombre = :nombre, valor = :valor WHERE id = :id';
        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':codigo_barra', $body['codigo_barra']);
            $stmt->bindParam(':nombre', $body['nombre']);
            $stmt->bindParam(':valor', $body['valor']);
            $stmt->bindParam(':id', $args['id']);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return $response->withJson(['message' => 'Actualización exitosa']);
            } else {
                return $response->withJson(['message' => 'Product not found'], 404);
            }
        } catch (\PDOException $e) {
            return $response->withJson(['message' => $e->getMessage()], 500);
        }
    });

    $this->delete('/{id:[0-9]+}', function (Request $request, Response $response, $args) {
        $db = $this->get('db');
        $query = 'DELETE FROM products WHERE id = :id';
        try {
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $args['id']);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return $response->withJson(['message' => 'Eliminación exitosa'], 204);
            } else {
                return $response->withJson(['message' => 'Product not found'], 404);
            }
        } catch (\PDOException $e) {
            return $response->withJson(['message' => $e->getMessage()], 500);
        }
    });

});
