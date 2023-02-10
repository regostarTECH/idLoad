<?php

ob_start();

date_default_timezone_set('Asia/Tashkent');

define("API_TOKEN",'Token');

function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_TOKEN."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}

$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$cid = $message->chat->id;
$mid = $message->message_id;
$text = $message->text;
$chat_id = $message->chat->id;
$message_id = $message->message_id;
$from_id = $message->from->id;
$name = $message->from->first_name;
$username = $message->from->username;


//  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░    
//  ░█████████░░███████████░░░░░████░░░░░████████░░░███████████░
//  ░███░░░███░░░░░░███░░░░░░░░██████░░░░███░░░███░░░░░░███░░░░░
//  ░███░░░░░░░░░░░░███░░░░░░░███░░███░░░███░░░███░░░░░░███░░░░░
//  ░█████████░░░░░░███░░░░░░██████████░░████████░░░░░░░███░░░░░
//  ░░░░░░░███░░░░░░███░░░░░░███░░░░███░░███░░░███░░░░░░███░░░░░
//  ░███░░░███░░░░░░███░░░░░░███░░░░███░░███░░░███░░░░░░███░░░░░
//  ░█████████░░░░░░███░░░░░░███░░░░███░░███░░░███░░░░░░███░░░░░
//  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ 


if($text=="/start"){
    bot('sendMessage',[
      'chat_id'=>$cid,
      'message_id'=>$mid,
      'parse_mode'=>'html',
      'text'=>"Salom $name! Men muloqot qila oladigan kompyuter dasturiman.\n\nMen sizga <b>id</b> raqam, telegramdagi <b>nik</b>ingiz va <b>username</b>ngiz haqida tezkor habar beraman.\n\n👤 Вы\n ├ <b>id:</b> <code>$chat_id</code>\n ├ <b>Telegramdagi ism:</b> $name\n └ <b>username: </b><b><a href='tg://user?id=$from_id'>$username</a></b>\n
      \nDasturiy ta'minot <a href='www.php.net'><b>PHP</b></a>.\nDasturchi: <a href='https://t.me/tegoMax'><b>tegoMax</b></a> janoblari.", 
    ]);
}
?>
