<?php

namespace Controllers;

use VK\Client\VKApiClient;

class Vk extends Controller
{
    protected $method;
    protected $vk_token;
    protected $group;
    protected $files_url = __DIR__.'/../files/';
    protected $photo_url = __DIR__.'/../files/images/';

    public function __construct()
    {
        parent::__construct();
        $this->method = new VKApiClient();
        $this->vk_token = $_ENV['VK_TOKEN'];
        $this->group = $_ENV['VK_GROUP_ID'];

    }

    public function sendWallPost()
    {
        $caption  = file($this->files_url.'telegram_caption');
        $photo_id = file($this->files_url.'vk_photo_id');

        $this->method->wall()->post($this->vk_token,
            [
                'owner_id'    => -$this->group,
                'from_group'  => 1,
                'message'     => $caption[0],
                'attachments' => implode(',', $photo_id)
            ]
        );

        unlink($this->files_url.'telegram_caption');
        unlink($this->files_url.'vk_photo_id');
    }

    public function uploadPhoto()
    {

        $name_photo = array_slice(scandir($this->photo_url), 2);
        $address             = $this->method->photos()->getWallUploadServer($this->vk_token,
            ['group_id' => $this->group]);
        $photo               = $this->method->getRequest()->upload(
            $address['upload_url'],
            'photo',
            $this->photo_url.$name_photo[0]
        );
        $response_save_photo = $this->method->photos()->saveWallPhoto($this->vk_token,
            [
                'group_id' => $this->group,
                'server'   => $photo['server'],
                'photo'    => $photo['photo'],
                'hash'     => $photo['hash'],
            ]
        );

        file_put_contents
        (
            $this->files_url.'vk_photo_id',
            'photo'.print_r($response_save_photo[0]['owner_id'].'_'.$response_save_photo[0]['id'], 1)."\n",
            FILE_APPEND
        );
        unlink($this->photo_url.$name_photo[0]);




    }


}
