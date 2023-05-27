<?php

class Article
{
    public $id;
    public $writerId;
    public $title;
    public $content;
    public $articlePhoto;
    public $articleVideo;
    public $publisherName;
    public $publishedDate;
    public $articleType;

    public function __construct(
        $id, $writerId, $title, $content, $articlePhoto, $articleVideo,
        $publisherName, $publishedDate, $articleType
    ) {
        $this->id = $id;
        $this->writerId = $writerId;
        $this->title = $title;
        $this->content = $content;
        $this->articlePhoto = $articlePhoto;
        $this->articleVideo = $articleVideo;
        $this->publisherName = $publisherName;
        $this->publishedDate = $publishedDate;
        $this->articleType = $articleType;
    }

    public function toMap()
    {
        return [
            'id' => $this->id,
            'writer_id' => $this->writerId,
            'title' => $this->title,
            'content' => $this->content,
            'article_photo' => $this->articlePhoto,
            'article_video' => $this->articleVideo,
            'publisher_name' => $this->publisherName,
            'published_date' => $this->publishedDate,
            'article_type' => $this->articleType,
        ];
    }

    public static function fromMap($map)
    {
        return new Article(
            $map['id'], $map['writer_id'], $map['title'], $map['content'],
            $map['article_photo'], $map['article_video'], $map['publisher_name'],
            $map['published_date'], $map['article_type']
        );
    }
}