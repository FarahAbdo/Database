<?php

class DatabaseController
{
    private $pdo;

    public function __construct($config)
    {
        $dsn = "{$config['driver']}:{$config['path']}";
        $this->pdo = new PDO($dsn);
        $this->createTables();
    }

    private function createTables()
    {
        // Execute the SQL queries to create the writers and articles tables
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS writers (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                profile_photo TEXT
            )
        ');

        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS articles (
                id INTEGER PRIMARY KEY,
                writer_id INTEGER,
                title TEXT NOT NULL,
                content TEXT NOT NULL,
                article_photo TEXT,
                article_video TEXT,
                publisher_name TEXT NOT NULL,
                published_date TEXT,
                article_type TEXT NOT NULL,
                FOREIGN KEY(writer_id) REFERENCES writers(id)
            )
        ');
    }

    // Writer methods
    // Implement the methods for managing writers
    public function getAllWriters()
{
    $stmt = $this->pdo->query('SELECT * FROM writers');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array_map(function ($row) {
        return Writer::fromMap($row);
    }, $rows);
}

public function getWriterById($id)
{
    $stmt = $this->pdo->prepare('SELECT * FROM writers WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? Writer::fromMap($row) : null;
}

public function addWriter($writer)
{
    $stmt = $this->pdo->prepare('INSERT INTO writers (name, profile_photo) VALUES (:name, :profile_photo)');
    $stmt->execute([
        ':name' => $writer->name,
        ':profile_photo' => $writer->profilePhoto,
    ]);
    $writer->id = $this->pdo->lastInsertId();
}

public function updateWriter($writer)
{
    $stmt = $this->pdo->prepare('UPDATE writers SET name = :name, profile_photo = :profile_photo WHERE id = :id');
    $stmt->execute([
        ':id' => $writer->id,
        ':name' => $writer->name,
        ':profile_photo' => $writer->profilePhoto,
    ]);
}

public function deleteWriterById($id)
{
    $stmt = $this->pdo->prepare('DELETE FROM writers WHERE id = :id');
    $stmt->execute([':id' => $id]);
}

    // Article methods
    // Implement the methods for managing articles
    public function getAllArticles()
{
    $stmt = $this->pdo->query('SELECT * FROM articles');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array_map(function ($row) {
        return Article::fromMap($row);
    }, $rows);
}

public function getArticleById($id)
{
    $stmt = $this->pdo->prepare('SELECT * FROM articles WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? Article::fromMap($row) : null;
}

public function addArticle($article)
{
    $stmt = $this->pdo->prepare('INSERT INTO articles (writer_id, title, content, article_photo, article_video, publisher_name, published_date, article_type) VALUES (:writer_id, :title, :content, :article_photo, :article_video, :publisher_name, :published_date, :article_type)');
    $stmt->execute([
        ':writer_id' => $article->writerId,
        ':title' => $article->title,
        ':content' => $article->content,
        ':article_photo' => $article->articlePhoto,
        ':article_video' => $article->articleVideo,
        ':publisher_name' => $article->publisherName,
        ':published_date' => $article->publishedDate,
        ':article_type' => $article->articleType,
    ]);
    $article->id = $this->pdo->lastInsertId();
}

public function updateArticle($article)
{
    $stmt = $this->pdo->prepare('UPDATE articles SET writer_id = :writer_id, title = :title, content = :content, article_photo = :article_photo, article_video = :article_video, publisher_name = :publisher_name, published_date = :published_date, article_type = :article_type WHERE id = :id');
    $stmt->execute([
        ':id' => $article->id,
        ':writer_id' => $article->writerId,
        ':title' => $article->title,
        ':content' => $article->content,
        ':article_photo' => $article->articlePhoto,
        ':article_video' => $article->articleVideo,
        ':publisher_name' => $article->publisherName,
        ':published_date' => $article->publishedDate,
        ':article_type' => $article->articleType,
    ]);
}

public function deleteArticleById($id)
{
    $stmt = $this->pdo->prepare('DELETE FROM articles WHERE id = :id');
    $stmt->execute([':id' => $id]);
}
}