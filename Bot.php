<?php

include('vendor/autoload.php');
use Controllers\Telegram;
use Controllers\Vk;

class Bot
{
    private $telegram;
    private $vk;
    private $updates;
    public $command;

    public function __construct()
    {
        $this->telegram = new Telegram();
        $this->vk = new Vk();
        $this->updates = $this->telegram->method->getWebhookUpdate();
        $this->command = strtolower($this->updates['message']['text']);
        if($this->updates['channel_post']['photo']){
            $this->telegram->downloadPhoto($this->updates['channel_post']);
            $this->vk->uploadPhoto();
        }

    }

    public function sendPostInVk()
    {

        if(file_exists('files/vk_photo_id')){
            $this->vk->sendWallPost();
            return $this->responseBot('Пост загружен');
        }

       return $this->responseBot('Не могуй найти пост,попробуйте еще раз');

    }

    public function responseBot ($text)
    {
        return $this->telegram->method->sendMessage(['chat_id' => $this->updates['message']['chat']['id'],'text' => $text]);
    }

    public function setWebHook()
    {
        $url = 'https://'.$_SERVER['HTTP_HOST'].'/Bot.php';
        return $this->telegram->method->setWebhook(['url' => $url]);

    }

}

$bot = new Bot();

if($bot->command == '/start'){
    $bot->responseBot('Привет,добавь меня в канал чтобы я мог видеть последние посты.');
}
if($bot->command == '/postvk'){
    $bot->sendPostInVk();
}








//$bot->setWebHook();

//https://oauth.vk.com/authorize?client_id=51444922&scope=offline,wall,photos&redirect_uri=https://oauth.vk.com/blank.html&display=page&v=5.37&response_type=token
