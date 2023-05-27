<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/database_controller.php';
require __DIR__ . '/writer.php';
require __DIR__ . '/article.php';

$config = require __DIR__ . '/config.php';
$db = new DatabaseController($config['database']);

$app = AppFactory::create();

// Add routes for managing writers and articles
$app->get('/writers', function (Request $request, Response $response) use ($db) {
    $writers = $db->getAllWriters();
    $writerMaps = array_map(function ($writer) {
        return $writer->toMap();
    }, $writers);
    $response->getBody()->write(json_encode($writerMaps));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/writers/{id}', function (Request $request, Response $response, $args) use ($db) {
    $writer = $db->getWriterById($args['id']);
    if ($writer) {
        $response->getBody()->write(json_encode($writer->toMap()));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        return $response->withStatus(404);
    }
});

$app->post('/writers', function (Request $request, Response $response) use ($db) {
    $data = json_decode($request->getBody()->getContents(), true);
    $writer = Writer::fromMap($data);
    $db->addWriter($writer);
    $response->getBody()->write(json_encode($writer->toMap()));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
});

$app->put('/writers/{id}', function (Request $request, Response $response, $args) use ($db) {
    $data = json_decode($request->getBody()->getContents(), true);
    $writer = Writer::fromMap($data);
    $writer->id = $args['id'];
    $db->updateWriter($writer);
    return $response->withStatus(204);
});

$app->delete('/writers/{id}', function (Request $request, Response $response, $args) use ($db) {
    $db->deleteWriterById($args['id']);
    return $response->withStatus(204);
});

$app->get('/articles', function (Request $request, Response $response) use ($db) {
    $articles = $db->getAllArticles();
    $articleMaps = array_map(function ($article) {
        return $article->toMap();
    }, $articles);
    $response->getBody()->write(json_encode($articleMaps));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/articles/{id}', function (Request $request, Response $response, $args) use ($db) {
    $article = $db->getArticleById($args['id']);
    if ($article) {
        $response->getBody()->write(json_encode($article->toMap()));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        return $response->withStatus(404);
    }
});

$app->post('/articles', function (Request $request, Response $response) use ($db) {
    $data = json_decode($request->getBody()->getContents(), true);
    $article = Article::fromMap($data);
    $db->addArticle($article);
    $response->getBody()->write(json_encode($article->toMap()));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
});

$app->put('/articles/{id}', function (Request $request, Response $response, $args) use ($db) {
    $data = json_decode($request->getBody()->getContents(), true);
    $article = Article::fromMap($data);
    $article->id = $args['id'];
    $db->updateArticle($article);
    return $response->withStatus(204);
});

$app->delete('/articles/{id}', function (Request $request, Response $response, $args) use ($db) {
    $db->deleteArticleById($args['id']);
    return $response->withStatus(204);
});

$app->run();