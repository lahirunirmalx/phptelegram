<?php

namespace phptelegram;

class TelegramClient
{
    private $botKey;
    private $openChats = array();
public function __construct($botKey)
{
    $this->botKey = $botKey;
}
public function registerChat($chatId){
    $key =  md5($chatId);
    if(!isset($this->openChats[$key])){
        $this->openChats[$key] = $chatId;
    }
}
public function unRegisterChat($chatId){
        $key =  md5($chatId);
        if(isset($this->openChats[$key])){
            unset($this->openChats[$key]);
        }
    }


    function dumpToBot($originalFile, $apiToken, $chat_id,$message,$type,$upload)
    {

        $typeMap = array(
            'photo' =>'sendPhoto',
            'video' =>'sendVideo',
            'animation' =>'sendAnimation',
        );

        $bot_url = "https://api.telegram.org/bot$apiToken/";
        $url = $bot_url .$typeMap[$type] ."?chat_id=" . $chat_id;

        $post_fields = array(
            'chat_id' => $chat_id,
        );

        if($upload){
            $post_fields[$type] = new \CURLFile(realpath($originalFile));
        }else{
            $post_fields[$type] = $originalFile;
        }
        if($message){
            $post_fields['caption'] = $message;
        }

        $ch = curl_init();
        if($upload) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type:multipart/form-data"
            ));
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            print_r($error_msg);
            var_dump($output);
        }
        curl_close($ch);
        return $this->getUploadedFileId($output,$type,$originalFile);

    }

    function dumpToBotPhoto($originalFile, $apiToken, $chat_id)
    {

        $bot_url = "https://api.telegram.org/bot$apiToken/";
        $url = $bot_url . "sendPhoto?chat_id=" . $chat_id;

        $post_fields = array(
            'chat_id' => $chat_id,
            'photo' => new CURLFile(realpath($originalFile))
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $output = curl_exec($ch);

        $this->getUploadedFileId($output,null,$originalFile);
        curl_close($ch);
    }

    function dumpToBotSendVideo($originalFile, $apiToken, $chat_id, $message)
    {

        $bot_url = "https://api.telegram.org/bot$apiToken/";
        $url = $bot_url . "sendVideo?chat_id=" . $chat_id;

        $post_fields = array(
            'chat_id' => $chat_id,
            'video' => new CURLFile(realpath($originalFile)),
            'caption' => $message
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $output = curl_exec($ch);

        $this->getUploadedFileId($output);
        curl_close($ch);
    }

    function dumpToBotsendAnimation($originalFile, $apiToken, $chat_id)
    {

        $bot_url = "https://api.telegram.org/bot$apiToken/";
        $url = $bot_url . "sendAnimation?chat_id=" . $chat_id;

        $post_fields = array(
            'chat_id' => $chat_id,
            'animation' => new CURLFile(realpath($originalFile))
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type:multipart/form-data"
        ));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $output = curl_exec($ch);

        $this->getUploadedFileId($output,null,$originalFile);
        curl_close($ch);
    }


    function getUploadedFileId($output,$type,$originalFile)
    {
        $data = json_decode($output, true);
        $returnDate = ['type' => $type];
        if($data['ok']){
            if($type =='photo'){
                $returnDate['file_id'] = $data['result'][$type][0]['file_id'];
            }else{
                $returnDate['file_id'] = $data['result'][$type]['file_id'];
            }
        }else{
            echo " Error \n";
            print_r($data);
            if($data['error_code'] == 400 && $data['description'] = 'Bad Request: file must be non-empty' ){
                unlink($originalFile);
            }
        }
        return $returnDate;
    }
}