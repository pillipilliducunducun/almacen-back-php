<?php

namespace Src\Controllers;

use PDO;
use Slim\Http\Request;
use Slim\Http\Response;

class ProductController
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllProducts(Request $request, Response $response)
    {
        $query = 'SELECT * FROM products';
        try {
            $stmt = $this->db->query($query);
            $products = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $response->withJson($products);
        } catch (\PDOException $e) {
            return $response->withJson(['message' => $e->getMessage()], 500);
        }
    }

    public function createProduct(Request $request, Response $response)
    {
        $body = $request->getParsedBody();
        $query = 'INSERT INTO products (codigo_barra, nombre, valor) VALUES (:codigo_barra, :nombre, :valor)';
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':codigo_barra', $body['codigo_barra']);
            $stmt->bindParam(':nombre', $body['nombre']);
            $stmt->bindParam(':valor', $body['valor']);
            $stmt->execute();

            return $response->withJson(['message' => 'Inserción exitosa'], 201);
        } catch (\PDOException $e) {
            return $response->withJson(['message' => $e->getMessage()], 400);
        }
    }

    public function updateProduct(Request $request, Response $response, $args)
    {
        $productId = $args['id'];
        $body = $request->getParsedBody();
        $query = 'UPDATE products SET codigo_barra = :codigo_barra, nombre = :nombre, valor = :valor WHERE id = :id';

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':codigo_barra', $body['codigo_barra']);
            $stmt->bindParam(':nombre', $body['nombre']);
            $stmt->bindParam(':valor', $body['valor']);
            $stmt->bindParam(':id', $productId);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $response->withJson(['message' => 'Actualización exitosa']);
            } else {
                return $response->withJson(['message' => 'Product not found'], 404);
            }
        } catch (\PDOException $e) {
            return $response->withJson(['message' => $e->getMessage()], 500);
        }
    }

    public function getProductById(Request $request, Response $response, $args)
    {
        $productId = $args['id'];
        $query = 'SELECT * FROM products WHERE id = :id';

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $productId);
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
    }

    public function deleteProduct(Request $request, Response $response, $args)
    {
        $productId = $args['id'];
        $query = 'DELETE FROM products WHERE id = :id';

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $productId);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $response->withJson(['message' => 'Eliminación exitosa'], 204);
            } else {
                return $response->withJson(['message' => 'Product not found'], 404);
            }
        } catch (\PDOException $e) {
            return $response->withJson(['message' => $e->getMessage()], 500);
        }
    }
}
