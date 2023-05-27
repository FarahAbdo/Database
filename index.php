<!-- the PHP code to connect to the database, then I need to create a RESTful API that exposes the database functionality as HTTP endpoints. 
I can use a lightweight framework like Slim or Lumen to create the API. 
The API should have endpoints for retrieving data from the database and adding data to the database -->

<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/database_controller.php';
require __DIR__ . '/writer.php';
require __DIR__ . '/article.php';

$app = AppFactory::create();

// Get all writers
$app->get('/writers', function (Request $request, Response $response, $args) {
    $db = new DatabaseController();
    $writers = $db->getAllWriters();
    $response->getBody()->write(json_encode($writers));
    return $response->withHeader('Content-Type', 'application/json');
});

// Add an article
$app->post('/articles', function (Request $request, Response $response, $args) {
    $db = new DatabaseController();

    // Get the request body and decode it as JSON
    $data = json_decode($request->getBody()->getContents(), true);

    // Check if required fields are present
    $requiredFields = ['writer_id', 'title', 'content', 'publisher_name', 'article_type'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(["error" => "$field is required"]));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    $article = new Article(
        $data['writer_id'],
        $data['title'],
        $data['content'],
        $data['article_photo'],
        $data['article_video'],
        $data['publisher_name'],
        $data['published_date'],
        $data['article_type']
    );

    try {
        $id = $db->insertArticle($article);
    } catch (\Exception $e) {
        $response = $response->withStatus(400);
        $response->getBody()->write(json_encode(["error" => $e->getMessage()]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    $response->getBody()->write(json_encode(["message" => "Inserted article with ID: " . $id]));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
