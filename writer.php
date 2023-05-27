<?php

class Writer
{
    public $id;
    public $name;
    public $profilePhoto;

    public function __construct($id, $name, $profilePhoto)
    {
        $this->id = $id;
        $this->name = $name;
        $this->profilePhoto = $profilePhoto;
    }

    public function toMap()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'profile_photo' => $this->profilePhoto,
        ];
    }

    public static function fromMap($map)
    {
        return new Writer($map['id'], $map['name'], $map['profile_photo']);
    }
}