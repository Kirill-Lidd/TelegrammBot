<?php

namespace Controllers;

use Telegram\Bot\Api;

class Telegram extends Controller
{

    protected $token;
    protected $files_url = __DIR__.'/../files/';
    protected $photo_url = __DIR__.'/../files/images/';
    public $method;

    public function __construct()
    {
        parent::__construct();
        $this->token = $_ENV['TELEGRAM_TOKEN'];
        $this->method = new Api($this->token);
    }


    public function downloadPhoto($data)
    {

        if ( ! is_dir($this->files_url)) {
            mkdir($this->files_url);
        }

        if ( ! is_dir($this->photo_url)) {
            mkdir($this->photo_url);
        }

        if ($data['caption']) {
            file_put_contents(
                $this->files_url.'telegram_caption',
                print_r($data['caption'], 1)."\n"
            );
        }

        $getPhoto = $this->method->getFile(['file_id' => $data['photo'][0]['file_id']]);
        $url = 'https://api.telegram.org/file/bot'.$this->token.'/'.$getPhoto['file_path'];
        file_put_contents($this->photo_url.'photo.jpg', file_get_contents($url));

    }
}
