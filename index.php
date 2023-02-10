<?php
ob_start();
date_Default_timezone_set('Asia/Tashkent');

define("API_TOKEN",'token');
$admin = "id admin";
$bot = bot('getme',['bot'])->result->username;
$soat = date('H:i');
$sana = date("d.m.Y");

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
$photo = $message->photo;
$chat_id = $message->chat->id;
$message_id = $message->message_id;
$from_id = $message->from->id;
$name = $message->from->first_name;
$last = $message->from->last_name;
$username = $message->from->username;
$bio = $message->from->about;
$tc = $update->message->chat->type;

$contact = $message->contact;
$contact_id = $contact->user_id;
$contact_user = $contact->username;
$contact_name = $contact->first_name;
$phone = $contact->phone_number;

$cdata = $update->callback_query->data;
$qid = $update->callback_query->id;
$cid2 = $update->callback_query->message->chat->id;
$mid2 = $update->callback_query->message->message_id;
$fromid = $update->callback_query->from->id;
$callname = $update->callback_query->from->first_name;
$calluser = $update->callback_query->from->username;
$surname = $update->callback_query->from->last_name;
$about = $update->callback_query->from->about;



//███████████████████████████████████████████████████████
//█▄─▄▄─█▄─▄█▄─▄███▄─▄▄─█─▄▄▄▄███▄─▄▄▀██▀▄─██─▄─▄─██▀▄─██
//██─▄████─███─██▀██─▄█▀█▄▄▄▄─████─██─██─▀─████─████─▀─██
//▀▄▄▄▀▀▀▄▄▄▀▄▄▄▄▄▀▄▄▄▄▄▀▄▄▄▄▄▀▀▀▄▄▄▄▀▀▄▄▀▄▄▀▀▄▄▄▀▀▄▄▀▄▄▀

if(!is_dir("data") || !is_dir("users")){
    mkdir("data");
    mkdir("users");
}

if(!is_dir("users/$from_id")){
    mkdir("users/$from_id");
}


@$users = json_decode(file_get_contents("data/users.json"),true);
@$data = json_decode(file_get_contents("data/data.json"),true);
@$taskdata = json_decode(file_get_contents("data/taskdata.json"),true);
@$fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
@$cuser = json_decode(file_get_contents("users/$fromid/data.json"),true);

if(!in_array($from_id, $users["userlist"]) == true) {
    $users["userlist"][]="$from_id";
    $users = json_encode($users,true);
    file_put_contents("data/users.json",$users);
}

if($data['bot']['minimal']){
}else{
    $data["bot"]['minimal']="50000";
    $data["bot"]['ref']="15";
    $data["bot"]['vipch']="-100"; #VIP sotib olganlar uchun kanal
    $data["bot"]['refch']="-100";
    $data["bot"]['paych']="-100";
    $data["bot"]['payhisch']="-100"; #Cheklari va pul yechish so'rovlarni tekshirish
    $data = json_encode($data,true);
    file_put_contents("data/data.json",$data);
}


function joinchat($id){
    global $mid, $name;
    $array = array("inline_keyboard");
    $data = json_decode(file_get_contents("data/data.json"),true);
    $chs = $data["channellist"];
    if(count($chs) <= 0){
        return true;
        }else{
    for($i=0;$i<=count($chs) -1;$i++){
    $url = $chs[$i];
    $r = $i+1;
         $ret = bot("getChatMember",[
             "chat_id"=>"@$url",
             "user_id"=>$id,
             ]);
    $stat = $ret->result->status;
             if((($stat=="creator" or $stat=="administrator" or $stat=="member"))){
    $array['inline_keyboard']["$i"][0]['text'] = "✅ $r - Kanal";
    $array['inline_keyboard']["$i"][0]['url'] = "https://t.me/$url";
             }else{
    $array['inline_keyboard']["$i"][0]['text'] = "❌ $r - Kanal";
    $array['inline_keyboard']["$i"][0]['url'] = "https://t.me/$url";
    $uns = true;
    }
    }
    $array['inline_keyboard']["$i"][0]['text'] = "☑️ Tastiqlash";
    $array['inline_keyboard']["$i"][0]['callback_data'] = "check";
    if($uns == true){
         bot('sendMessage',[
             'chat_id'=>$id,
             'text'=>"<b>Assalomu aleykum, </b> <a href='tg://user?id=$from_id'>$name</a>\n\n<i>Botdan foydalanish uchun ushbu kanallarimizga a'zo bo'ling 👇</i>",
             'parse_mode'=>'html',
    'disable_web_page_preview'=>true,
    'reply_markup'=>json_encode($array),
    ]);  
    exit();
    return false;
    }else{
    return true;
    }
    }
    }
    



//█▀▄▀█ █▀▀ █▀▀▄ █░░█ █▀▀ 
//█░▀░█ █▀▀ █░░█ █░░█ ▀▀█ 
//▀░░░▀ ▀▀▀ ▀░░▀ ░▀▀▀ ▀▀▀

$menu = json_encode([
    'resize_keyboard'=>true,
    'keyboard'=>[
    [['text'=>"⚜️ VIP"],['text'=>"🚀 Vazifalar"]],
    [['text'=>"📱 Profil"]],
    [['text'=>"💰 Balans"],['text'=>"📥 Balans To'ldirish"]],
    [['text'=>"💳 Hamyonlar"]],
    [['text'=>"🖇 Referal"], ['text'=>"📄 To'lovlar"]],
    ]
    ]);

$admin_panel = json_encode([
    'resize_keyboard'=>true,
    'keyboard'=>[
    [['text'=>"🤖 Bot sozlamalari"]],
    [['text'=>"📱 Hisob parametrlari"],['text'=>"⚜️ VIP parametrlari"]],
    [['text'=>"📨 Barchaga xabar yuborish"]],
    [['text'=>"📈 Statistika"]],
    ]
    ]);

$hisob_panel = json_encode([
    'resize_keyboard'=>true,
    'keyboard'=>[
    [['text'=>"💢 Ban berish"],['text'=>"❇️ Bandan olish"]],
    [['text'=>"📱 Hisobni tekshirish"],['text'=>"💰 Hisobni to'ldirish"]],
    [['text'=>"⬅️ Back"]],
    ]
    ]);

$vip_panel = json_encode([
    'resize_keyboard'=>true,
    'keyboard'=>[
    [['text'=>"〽️ VIP tarif qo'shish"]],
    [['text'=>"🔱 VIP tarifni o'zgartirish"],['text'=>"💢 VIP tarifni o'chirish"]],
    //[['text'=>"🚀 Vazifa biriktirish"]],
    [['text'=>"⬅️ Back"]],
    ]
    ]);

$bot_panel = json_encode([
    'resize_keyboard'=>true,
    'keyboard'=>[
    [['text'=>"💰 Minimal pul yechish"]],
    [['text'=>"🖇 Referal foizi"],['text'=>"🔰 Kanal biriktirish"]],
    [['text'=>"⬅️ Back"]],
    ]
    ]);


//█▀▀ ▀▀█▀▀ █▀▀█ ▀▀█▀▀ 
//▀▀█ ░░█░░ █▄▄█ ░░█░░ 
//▀▀▀ ░░▀░░ ▀░░▀ ░░▀░░

if($text =="📈 Statistika" && $tc == "private"){	
    if(in_array($from_id, $users["userlist"]) == true) {
    $all = count($users["userlist"]);
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"🤖 Bot Statistikaasi: 
            
📌Hamma userlar $all ta",
            ]);
    }
}


if(in_array($from_id, $users["blocklist"])) {
bot('sendmessage',[
    'chat_id'=>$chat_id,
    'text'=>"💢 Siz botdan blocklangansiz! Foydalanish huquqiga ega emassiz",
'reply_markup'=>json_encode(['KeyboardRemove'=>[
],'remove_keyboard'=>true
])
]);
exit();
}

if(in_array($fromid, $users["blocklist"])) {
bot('answerCallbackQuery',[
   'callback_query_id'=>$qid,
   'text'=>"💢 Siz botdan blocklangansiz! Foydalanish huquqiga ega emassiz",
   'show_alert' => 1,
]);
bot('deletemessage',[
    'chat_id'=>$cid2,
    'message_id'=>$mid2,
]);
exit();
}







//░█████╗░██████╗░███╗░░░███╗██╗███╗░░██╗  ██████╗░░█████╗░███╗░░██╗███████╗██╗░░░░░
//██╔══██╗██╔══██╗████╗░████║██║████╗░██║  ██╔══██╗██╔══██╗████╗░██║██╔════╝██║░░░░░
//███████║██║░░██║██╔████╔██║██║██╔██╗██║  ██████╔╝███████║██╔██╗██║█████╗░░██║░░░░░
//██╔══██║██║░░██║██║╚██╔╝██║██║██║╚████║  ██╔═══╝░██╔══██║██║╚████║██╔══╝░░██║░░░░░
//██║░░██║██████╔╝██║░╚═╝░██║██║██║░╚███║  ██║░░░░░██║░░██║██║░╚███║███████╗███████╗
//╚═╝░░╚═╝╚═════╝░╚═╝░░░░░╚═╝╚═╝╚═╝░░╚══╝  ╚═╝░░░░░╚═╝░░╚═╝╚═╝░░╚══╝╚══════╝╚══════╝


if($text =="/panel" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Admin panelga hush kelibsiz",
        'reply_markup'=>$admin_panel,
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}

if($text =="⬅️ Back" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Admin panelga qaytingiz",
        'reply_markup'=>$admin_panel,
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($text =="⚜️ VIP parametrlari" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Quyidagi menyulardan foydalanishingiz mumkin",
        'reply_markup'=>$vip_panel,
    ]);
}







if($text =="🔱 VIP tarifni o'zgartirish" && $from_id == $admin){	
    $vipp = $data["vip"];
    $array = array("inline_keyboard");
    $c = count($vipp);
    $n = 0; $p = 0;
    for($i=0;$i<$c;$i+=3){
        $k=$i+1; $h=$k+1;

        $array['inline_keyboard']["$n"][0]['text'] = $vipp["$i"]['name'];
        $array['inline_keyboard']["$n"][0]['callback_data'] = "edit=$i"; $p++;

        if( $c > $p){
        $array['inline_keyboard']["$n"][1]['text'] = $vipp["$k"]['name'];
        $array['inline_keyboard']["$n"][1]['callback_data'] = "edit=$k"; $p++;
        }

        if( $c > $p ){
        $array['inline_keyboard']["$n"][2]['text'] = $vipp["$h"]['name'];
        $array['inline_keyboard']["$n"][2]['callback_data'] = "edit=$h"; $p++;
        }
        $n++;
    }

    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"🛠",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>[
                [['text'=>"⬅️ Back"]],
                ]]),
    ]);

    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b> 🛠 O'zgartirish kerak bo'lgan VIP tarifni tanlang </b>",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode($array),
    ]);
}


if((stripos($cdata,"edit=")!==false) && $fromid == $admin){	
    $data = json_decode(file_get_contents("data/data.json"),true);
    $cdatas = explode('=', $cdata);
    $vipp = $data["vip"][$cdatas[1]];
    $ctx = "⚜️ VIP tarif haqida:\n\nNomi: ".$vipp['name']."\nVazifasi: ".$vipp['task']." ta\nNarxi: ".$vipp['sum']." so'm\nKunlik daromad: ".$vipp['income'];
    bot('sendmessage',[
        'chat_id'=>$cid2,
        'text'=>$ctx."\n\n\nTarifni o'zgartirishdagi nomni kiriting",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>[
                [['text'=>"⬅️ Back"]],
                ]]),
    ]);
    $cuser = json_decode(file_get_contents("users/$fromid/data.json"),true);
    $cuser["userfild"]["$fromid"]["step"]="admin_vip_edit_name";
    $cuser["userfild"]["$fromid"]["vipid"]=intval($cdatas[1]);
    $cuser = json_encode($cuser,true);
    file_put_contents("users/$fromid/data.json",$cuser);
}


if($fuser["userfild"]["$from_id"]["step"]=="admin_vip_edit_name" && $text != "⬅️ Back" && $from_id == $admin){	
    $idc = $fuser["userfild"]["$from_id"]["vipid"];

    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>♻️ VIP tarif nomi o'zgartirildi\n\nQancha vazifa berilishini kiriting</b>",
        "parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_vip_edit_task";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);

    $data["vip"][$idc]['name'] = $text;
    $data = json_encode($data,true);
    file_put_contents("data/data.json",$data);
}

if($fuser["userfild"]["$from_id"]["step"]=="admin_vip_edit_task" && $text != "⬅️ Back" && $from_id == $admin){	
    $idc = $fuser["userfild"]["$from_id"]["vipid"];
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>♻️ VIP tarif vazifalari soni o'zgartirildi\n\nTarifga o'tgan foydalanuvchining referali tarifga o'tsa qancha bonus berilsin</b>",
        "parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_vip_edit_bonus";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);

    $data["vip"][$idc]['task'] = $text;
    $data = json_encode($data,true);
    file_put_contents("data/data.json",$data);
}

if($fuser["userfild"]["$from_id"]["step"]=="admin_vip_edit_bonus" && $text != "⬅️ Back" && $from_id == $admin){	
    $idc = $fuser["userfild"]["$from_id"]["vipid"];
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>♻️ VIP tarif referal bonusi o'zgartirildi\n\nTarifga o'tgan foydalanuvchining kunlik daromadini kiriting</b>",
        "parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_vip_edit_income";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);

    $data["vip"][$idc]['bonus'] = $text;
    $data = json_encode($data,true);
    file_put_contents("data/data.json",$data);
}


if($fuser["userfild"]["$from_id"]["step"]=="admin_vip_edit_income" && $text != "⬅️ Back" && $from_id == $admin){	
    $idc = $fuser["userfild"]["$from_id"]["vipid"];
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>♻️ VIP ning kunlik daromadi kiritildi \n\nTarifni narxini kiriting</b>",
        "parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_vip_edit_sum";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);

    $data["vip"][$idc]['income'] = $text;
    $data = json_encode($data,true);
    file_put_contents("data/data.json",$data);
}


if($fuser["userfild"]["$from_id"]["step"]=="admin_vip_edit_sum" && $text != "⬅️ Back" && $from_id == $admin){	
    $idc = $fuser["userfild"]["$from_id"]["vipid"];
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>♻️ Barcha malumot qayta ishlandi ✅</b>",
        "parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);

    $data["vip"][$idc]['sum'] = $text;
    $data = json_encode($data,true);
    file_put_contents("data/data.json",$data);
}


if($text =="〽️ VIP tarif qo'shish" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"〽️ Yangi VIP tarif qo'shish bo'limidasiz\n\n📌 Yangi VIP tarif uchun nom bering:\n\nMisol: <code>VIP 1</code>",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>[
                [['text'=>"⬅️ Back"]],
                ]]),
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_vip_add_name";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($fuser["userfild"]["$from_id"]["step"]=="admin_vip_add_name" && $text != "⬅️ Back" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>VIP ga ulangan foydalanuvchi nechta vazifa bajara oladi:</b>",
        "parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_vip_add_task";
    $fuser["userfild"]["$from_id"]["vipstep"]["name"]="$text";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($fuser["userfild"]["$from_id"]["step"]=="admin_vip_add_task" && $text != "⬅️ Back" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>VIP ga ulangan foydalanuvchini referali tarifga o'tsa bonus puli</b>",
        "parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_vip_add_bonus";
    $fuser["userfild"]["$from_id"]["vipstep"]["task"]="$text";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($fuser["userfild"]["$from_id"]["step"]=="admin_vip_add_bonus" && $text != "⬅️ Back" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>VIP ga ulangan foydalanuvchini kunlik daromadi</b>",
        "parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_vip_add_income";
    $fuser["userfild"]["$from_id"]["vipstep"]["bonus"]="$text";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}

if($fuser["userfild"]["$from_id"]["step"]=="admin_vip_add_income" && $text != "⬅️ Back" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>VIP ga ulanish narxi kiriting</b>",
        "parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_vip_add_sum";
    $fuser["userfild"]["$from_id"]["vipstep"]["income"]="$text";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($fuser["userfild"]["$from_id"]["step"]=="admin_vip_add_sum" && $text != "⬅️ Back" && $from_id == $admin){	
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["vipstep"]["sum"]="$text";

    $data = json_decode(file_get_contents("data/data.json"),true);
    $data["vip"][]= $fuser["userfild"]["$from_id"]["vipstep"];
    $data = json_encode($data,true);
    file_put_contents("data/data.json",$data);
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>✅ VIP muvaffaqiyatli yaratildi</b>",
        "parse_mode"=>"html",
    ]);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($text =="💢 VIP tarifni o'chirish" && $from_id == $admin){	
    $vipp = $data["vip"];
    $array = array("inline_keyboard");
    $c = count($vipp);
    $n = 0; $p = 0;
    for($i=0;$i<$c;$i+=3){
        $k=$i+1; $h=$k+1;
        
        $array['inline_keyboard']["$n"][0]['text'] = $vipp["$i"]['name'];
        $array['inline_keyboard']["$n"][0]['callback_data'] = "del=$i"; $p++;

        if( $c > $p){
        $array['inline_keyboard']["$n"][1]['text'] = $vipp["$k"]['name'];
        $array['inline_keyboard']["$n"][1]['callback_data'] = "del=$k"; $p++;
        }

        if( $c > $p ){
        $array['inline_keyboard']["$n"][2]['text'] = $vipp["$h"]['name'];
        $array['inline_keyboard']["$n"][2]['callback_data'] = "del=$h"; $p++;
        }
        $n++;
    }

    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b> 💢 </b>",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>[
                [['text'=>"⬅️ Back"]],
                ]]),
    ]);

    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b> ❌ O'chirish kerak bo'lgan VIP tarifni tanlang </b>",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode($array),
    ]);
}


if((stripos($cdata,"del=")!==false) && $fromid == $admin){	
    $data = json_decode(file_get_contents("data/data.json"),true);
    $cdatas = explode('=', $cdata);
    $vipp = $data["vip"][$cdatas[1]];
    $ctx = "⚜️ VIP tarif haqida:\n\nNomi: ".$vipp['name']."\nVazifasi: ".$vipp['task']." ta\nNarxi: ".$vipp['sum']." so'm\nKunlik daromad: ".$vipp['income'];
    bot('sendmessage',[
        'chat_id'=>$cid2,
        'text'=>$ctx."\n\n\n💁‍♂️ Tarifni rostdan o'chirmoqchimisiz ?",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>[
                [['text'=>"⬅️ Back"]],
                ]]),
    ]);
    $cuser = json_decode(file_get_contents("users/$fromid/data.json"),true);
    $cuser["userfild"]["$fromid"]["step"]="admin_vip_del";
    $cuser["userfild"]["$fromid"]["vipid"]=intval($cdatas[1]);
    $cuser = json_encode($cuser,true);
    file_put_contents("users/$fromid/data.json",$cuser);
}


if($fuser["userfild"]["$from_id"]["step"]=="admin_vip_del" && $text != "⬅️ Back" && $from_id == $admin){	
    $data = json_decode(file_get_contents("data/data.json"),true);
    $idc = $fuser["userfild"]["$from_id"]["vipid"];

    if(strtolower($text) == "ha"){
        unset($data["vip"][$idc]);
        $vipp = $data["vip"];
        bot('sendmessage',[
            'chat_id'=>$chat_id,
            'text'=>"<b>🤦‍♂️ VIP tarif o'chirib tashlandi</b>",
            "parse_mode"=>"html",
        ]);
        $data = json_encode($data,true);
        file_put_contents("data/data.json",$data);
    }else{
        bot('sendmessage',[
            'chat_id'=>$chat_id,
            'text'=>"<b>🙋‍♂️ Amal bekor qilindi</b> ",
            "parse_mode"=>"html",
        ]);
    }

    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($text =="📨 Barchaga xabar yuborish" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Foydalanuvchilarga xabar yuborish bo'limidasiz istalgan xabarni yuboring",
        'reply_markup'=>json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>[
                [['text'=>"⬅️ Back"]],
                ]]),
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_send";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}

if($fuser["userfild"]["$from_id"]["step"]=="admin_send" && $text != "⬅️ Back" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>Yuborilmoqda...</b>",
        "parse_mode"=>"html",
    ]);
    $users = json_decode(file_get_contents("data/users.json"),true);
    $numbers = $users["userlist"];
    for($z = 0;$z <= count($numbers)-1;$z++){
        bot('copymessage',[
            'chat_id'=>$numbers[$z],        
            "from_chat_id"=>$chat_id,
             'message_id'=>$message_id,
           ]);
   }
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>Xabar hammaga yuborildi✔️</b>",
        "parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($text =="📱 Hisob parametrlari" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Quyidagi menyulardan foydalanishingiz mumkin",
        'reply_markup'=>$hisob_panel,
    ]);
}

if($text =="💢 Ban berish" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Botdan foydalanish huquqini olib tashlash uchun blocklanuvchi foydalanuvchi id raqamini kiriting:",
        'reply_markup'=>json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>[
                [['text'=>"⬅️ Back"]],
                ]]),
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="banned";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}

if($fuser["userfild"]["$from_id"]["step"]=="banned" && $text != "⬅️ Back" && $from_id == $admin){	
    $users["blocklist"][]="$text";
    $users = json_encode($users,true);
    file_put_contents("data/users.json",$users);
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>❌ Endi <a href=''>Foydalanuvchi</a> botni ishlata olmaydi</b>",
"parse_mode"=>"html",
    ]);
    bot('sendmessage',[
        'chat_id'=>$text,
        'text'=>"<b>❌ Siz admin tomonidan blocklandingiz endi botdan foydalana olmaysiz</b>",
"parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}

if($text =="❇️ Bandan olish" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Botdan foydalanish huquqi berish uchun blocklangan foydalanuvchi id raqamini kiriting:",
        'reply_markup'=>json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>[
                [['text'=>"⬅️ Back"]],
                ]]),
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="unbanned";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}

if($fuser["userfild"]["$from_id"]["step"]=="unbanned" && $text != "⬅️ Back" && $from_id == $admin){	
    $pid = array_search('php', $users["blocklist"]);
    unset($users["blocklist"][$pid]);
    $users = json_encode($users,true);
    file_put_contents("data/users.json",$users);
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>🟢 Endi <a href=''>Foydalanuvchi</a> botni ishlata oladi</b>",
"parse_mode"=>"html",
    ]);
    bot('sendmessage',[
        'chat_id'=>$text,
        'text'=>"<b>🟢 Sizdagi cheklov admin tomonidan olib tashlandi. Endi bemalol botdan foydalana olasiz</b>",
"parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($text =="📱 Hisobni tekshirish" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Foydalanuvchi hisobini tekshirish uchun id raqamini kiriting:",
        'reply_markup'=>json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>[
                [['text'=>"⬅️ Back"]],
                ]]),
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_check";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}

if($fuser["userfild"]["$from_id"]["step"]=="admin_check" && $text != "⬅️ Back" && $from_id == $admin){	
    $duser = json_decode(file_get_contents("users/$text/data.json"),true);
    $sum = $duser["userfild"]["$text"]["sum"];
    $referal = $duser["userfild"]["$text"]["referal"];
    $rid = $duser["userfild"]["$text"]["refralid"];
    $vip = $duser["userfild"]["$text"]["VIP"]; $vname = $vip['name']; $vtask = $vip['task'];
    $vtx = "Sotib olmagansiz ❌";
    if($vip["status"] != "false"){
        $vtx = "Sotib olingan ✅
        💠 Tarif nomi: <i>$vname</i>
        〽️ Vazifalari: $vtask ta / kuniga";
    }
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>💠 Foydalanuvchi malumotlari

💰 Hisobi: $sum so'm
🖇 Referallari: $referal ta
💡 Taklif qilgan: $rid

⚜️ VIP: $vtx </b>",
"parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}

if($text =="💰 Hisobni to'ldirish" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Kimni hisobini o'zgartirmoqchisiz, 🆔 raqamini yuboring:",
        'reply_markup'=>json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>[
                [['text'=>"⬅️ Back"]],
                ]]),
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_dep_id";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}

if($fuser["userfild"]["$from_id"]["step"]=="admin_dep_id" && $text != "⬅️ Back" && $from_id == $admin){	
    $duser = json_decode(file_get_contents("users/$text/data.json"),true);
    $sum = $duser["userfild"]["$text"]["sum"];

    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Foydalanuvchi hisobida $sum so'm mavjud\n\nQanchaga o'rgartirmoqchisiz: \n\nMisol: -5000",
"parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_dep_sum";
    $fuser["userfild"]["$from_id"]["step_id"]="$text";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($fuser["userfild"]["$from_id"]["step"]=="admin_dep_sum" && $text != "⬅️ Back" && $from_id == $admin){	
    $tid = $fuser["userfild"]["$from_id"]["step_id"];
    $duser = json_decode(file_get_contents("users/$tid/data.json"),true);
    $rid = $duser["userfild"]["$tid"]["refralid"];
    $tsum = $duser["userfild"]["$tid"]["sum"] + $text;
    $duser["userfild"]["$tid"]["sum"] = $tsum;
    $duser = json_encode($duser,true);
    file_put_contents("users/$tid/data.json",$duser);
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"✅ Muvaffaqiyatli o'zgartirildi",
        "parse_mode"=>"html",
    ]);
    bot('sendmessage',[
        'chat_id'=>$tid,
        'text'=>"<b> 💰 Hisobingiz admin tomonidan <code>$text</code> so'mga to'ldirdi\n\n🟠 Hozirgi balansingiz: <code>$tsum</code> so'm</b>",
        "parse_mode"=>"html",
    ]);
    if($rid){
        $rsum = $text / 100 * $data['bot']['ref'];
        bot('sendmessage',[
            'chat_id'=>$rid,
            'text'=>"<b>🔆 Siz botimizga taklif qilgan bir do'stingiz hisobini to'ldirdi\n\n💰 Sizning hisobingizga +$rsum so'm qo'shib berildi </b>",
            "parse_mode"=>"html",
        ]);
        $ruser = json_decode(file_get_contents("users/$rid/data.json"),true);
        $rsum = $ruser["userfild"]["$rid"]["sum"] + $rsum;
        $ruser["userfild"]["$rid"]["sum"] = $rsum;
        $ruser = json_encode($ruser,true);
        file_put_contents("users/$rid/data.json",$ruser);
    }
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($text =="🤖 Bot sozlamalari" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Quyidagi menyulardan foydalanishingiz mumkin",
        'reply_markup'=>$bot_panel,
    ]);
}

if($text =="💰 Minimal pul yechish" && $from_id == $admin){	
    $minsum = $data["bot"]['minimal'];
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Foydalanuvchilar pul yechishi uchun minimal summani kiriting: \n\nHozirda minimal summa: $minsum so'm",
        'reply_markup'=>json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>[
                [['text'=>"⬅️ Back"]],
                ]]),
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_minsum";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}

if($fuser["userfild"]["$from_id"]["step"]=="admin_minsum" && $text != "⬅️ Back" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>✅ Muvaffaqiyatli o'zgartirish</b>",
        "parse_mode"=>"html",
    ]);
    $data["bot"]['minimal'] = $text;
    $data = json_encode($data,true);
    file_put_contents("data/data.json",$data);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($text =="🖇 Referal foizi" && $from_id == $admin){	
    $reff = $data["bot"]['ref'];
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Foydalanuvchilar referallari hisobini to'ldirganda qancha ulush olishadi: \n\nHozirda: $reff%",
        'reply_markup'=>json_encode([
            'resize_keyboard'=>true,
            'keyboard'=>[
                [['text'=>"⬅️ Back"]],
                ]]),
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="admin_reff";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}

if($fuser["userfild"]["$from_id"]["step"]=="admin_reff" && $text != "⬅️ Back" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>✅ Muvaffaqiyatli o'zgartirish</b>",
        "parse_mode"=>"html",
    ]);
    $data["bot"]['ref'] = $text;
    $data = json_encode($data,true);
    file_put_contents("data/data.json",$data);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


if($text =="🔰 Kanal biriktirish" && $from_id == $admin){	
    $chs = $data["channellist"];
    $array = array("inline_keyboard");
    for($i=0;$i<=count($chs) -1;$i++){
        $r = $i+1;
        $chu = $chs[$i];
        $array['inline_keyboard']["$i"][0]['text'] = "✅ $r - Kanal";
        $array['inline_keyboard']["$i"][0]['url'] = "https://t.me/$chu";
        $array['inline_keyboard']["$i"][1]['text'] = "❌";
        $array['inline_keyboard']["$i"][1]['callback_data'] = "delchan=$i";
    }
    $array['inline_keyboard']["$i"][0]['text'] = "➕ Kanal qo'shish";
    $array['inline_keyboard']["$i"][0]['callback_data'] = "admin_add_channel";
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>Majburiy Obuna uchun kanallarni sozlang:</b>",
        'reply_markup'=>json_encode($array),
        'parse_mode'=>'html',
    ]);
}

if((stripos($cdata,"delchan=")!==false) && $fromid == $admin){	
    $cdatas = explode('=', $cdata);
    $idc = $cdatas[1];
    unset($data["channellist"][$idc]);
    $data = json_encode($data,true);
    file_put_contents("data/data.json",$data);

    $data = json_decode(file_get_contents("data/data.json"),true);
    $chs = $data["channellist"];
    $array = array("inline_keyboard");
    for($i=0;$i<=count($chs) -1;$i++){
        $r = $i+1;
        $chu = $chs[$i];
        $array['inline_keyboard']["$i"][0]['text'] = "✅ $r - Kanal";
        $array['inline_keyboard']["$i"][0]['url'] = "https://t.me/$chu";
        $array['inline_keyboard']["$i"][1]['text'] = "❌";
        $array['inline_keyboard']["$i"][1]['callback_data'] = "delchan=$i";
    }
    $array['inline_keyboard']["$i"][0]['text'] = "➕ Kanal qo'shish";
    $array['inline_keyboard']["$i"][0]['callback_data'] = "admin_add_channel";
    bot('answerCallbackQuery',[
        'callback_query_id'=>$qid,
        'text'=>"❌ Kanal olib tashlandi",
        'show_alert' => 1,
     ]);
    bot('deleteMessage',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
    ]);
}

if($cdata =="admin_add_channel" && $fromid == $admin){
    bot('editMessageText',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
        'text'=>"<b>Majburiy Obunaga Qo'shmoqchi bo'lgan kanalga @$bot ni admin qiing va kanal username ni yuboring\nMisol: </b><code>icodernet</code>",
        "parse_mode"=>"html",
    ]);
    $cuser = json_decode(file_get_contents("users/$fromid/data.json"),true);
    $cuser["userfild"]["$fromid"]["step"]="admin_add_channel";
    $cuser = json_encode($cuser,true);
    file_put_contents("users/$fromid/data.json",$cuser);	
}

if($fuser["userfild"]["$from_id"]["step"]=="admin_add_channel" && $text != "⬅️ Back" && $from_id == $admin){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>✅ Muvaffaqiyatli qo'shildi</b>",
        "parse_mode"=>"html",
    ]);
    $chan = str_replace(" ","", $text);
    $chan = str_replace("@","", $chan);
    $data["channellist"][] = $chan;
    $data = json_encode($data,true);
    file_put_contents("data/data.json",$data);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}


//█▀▀ █▄░█ █▀▄   ▄▀█ █▀▄ █▀▄▀█ █ █▄░█   █▀█ ▄▀█ █▄░█ █▀▀ █░░
//██▄ █░▀█ █▄▀   █▀█ █▄▀ █░▀░█ █ █░▀█   █▀▀ █▀█ █░▀█ ██▄ █▄▄










//░██████╗████████╗░█████╗░██████╗░████████╗
//██╔════╝╚══██╔══╝██╔══██╗██╔══██╗╚══██╔══╝
//╚█████╗░░░░██║░░░███████║██████╔╝░░░██║░░░
//░╚═══██╗░░░██║░░░██╔══██║██╔══██╗░░░██║░░░
//██████╔╝░░░██║░░░██║░░██║██║░░██║░░░██║░░░
//╚═════╝░░░░╚═╝░░░╚═╝░░╚═╝╚═╝░░╚═╝░░░╚═╝░░░

if($text=="/start" && $tc == "private"){
    if(in_array($from_id, $users["userlist"]) == true){
            bot("sendMessage",[
            "chat_id"=>$cid,
            "text"=>"🗃 Siz Bosh Menyuga Qaytingiz",
            "parse_mode"=>"html",
            "reply_markup"=>$menu,
            ]);
        
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
}else{
    bot("sendMessage",[
        "chat_id"=>$cid,
        "text"=>"🎄 Assalomu Alaykum <a href='tg://user?id=$from_id'>$name</a>
        
✅ Sizni Avolonda ko'rib turganimizdan hursandmiz!

🟠 Quyidagi menyulardan foydalanishingiz mumkin",
        "parse_mode"=>"html",
        "reply_markup"=>$menu,
        ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["sum"]="0";
    $fuser["userfild"]["$from_id"]["referal"]="0";
    $fuser["userfild"]["$from_id"]["task"]['data']="false";
    $fuser["userfild"]["$from_id"]["card"]="false";
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser["userfild"]["$from_id"]["VIP"]["status"]="false";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
    }
}
elseif(strpos($text , '/start ') !== false) {
    $start = str_replace("/start ","",$text);
    if(in_array($from_id, $users["userlist"])) {
        bot('sendmessage',[
            'chat_id'=>$chat_id,
            'text'=>"$name G`irrom qila olmaysiz shu hol yana takrorlansa ban olasiz!
        
    🔻 Quyidagi tugmalardan foydalaning",
            "parse_mode"=>"html",
                'reply_markup'=>$menu,
            ]);	
    }
    else 
    {
    $data = json_decode(file_get_contents("data/data.json"),true);
    $ref = $data["bot"]['ref'];
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);	
    $inuser = json_decode(file_get_contents("users/$start/data.json"),true);
    $member = $inuser["userfild"]["$start"]["referal"];
    $memberplus = $member + 1;
        bot('sendmessage',[
        'chat_id'=>$start,
        'text'=>"💁‍♂️ Siz botga <a href='tg://user?id=$from_id'>$name</a> ni taklif qildingiz!
        
💸 Do'stingiz agar hisobini to'ldirsa sizga $ref foiz qo'shimcha mablag' olasiz",
        "parse_mode"=>"html",
        'reply_markup'=>$menu,
     ]);
        bot('sendmessage',[
            'chat_id'=>$chat_id,
            'text'=>"😊 Assalomu Alaykum <a href='tg://user?id=$from_id'>$name</a>

    ✅ Sizni Avolonda ko'rib turganimizdan hursandmiz!

    🟠 Quyidagi menyulardan foydalanishingiz mumkin",
                'parse_mode'=>'html',
            'reply_markup'=>$menu,
            ]);
    $inuser["userfild"]["$start"]["referal"]="$memberplus";
    $inuser = json_encode($inuser,true);
    file_put_contents("users/$start/data.json",$inuser);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["sum"]="0";
    $fuser["userfild"]["$from_id"]["referal"]="0";
    $fuser["userfild"]["$from_id"]["task"]['data']="false";
    $fuser["userfild"]["$from_id"]["card"]="false";
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser["userfild"]["$from_id"]["VIP"]["status"]="false";
    $fuser["userfild"]["$from_id"]["refralid"]="$start";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);	
    }
}

if($cdata =="back"){
    bot('editMessageText',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
        'text'=>"<b>Bosh menyuga qaytingiz</b>",
        "parse_mode"=>"html",
    ]);
    $cuser = json_decode(file_get_contents("users/$fromid/data.json"),true);
    $cuser["userfild"]["$fromid"]["step"]="none";
    $cuser = json_encode($cuser,true);
    file_put_contents("users/$fromid/data.json",$cuser);	
}

if($cdata =="check" && joinchat($fromid)){
    bot('editMessageText',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
        'text'=>"✅ Kanallarga a'zo boldingiz endi botdan foydalanishingiz mumkin",
        "parse_mode"=>"html",
    ]);
    $cuser = json_decode(file_get_contents("users/$fromid/data.json"),true);
    $cuser["userfild"]["$fromid"]["step"]="none";
    $cuser = json_encode($cuser,true);
    file_put_contents("users/$fromid/data.json",$cuser);	
}


//█▀▀ █▄░█ █▀▄   █▀ ▀█▀ ▄▀█ █▀█ ▀█▀
//██▄ █░▀█ █▄▀   ▄█ ░█░ █▀█ █▀▄ ░█░












//░██╗░░░░░░░██╗░█████╗░██╗░░░░░██╗░░░░░███████╗████████╗
//░██║░░██╗░░██║██╔══██╗██║░░░░░██║░░░░░██╔════╝╚══██╔══╝
//░╚██╗████╗██╔╝███████║██║░░░░░██║░░░░░█████╗░░░░░██║░░░
//░░████╔═████║░██╔══██║██║░░░░░██║░░░░░██╔══╝░░░░░██║░░░
//░░╚██╔╝░╚██╔╝░██║░░██║███████╗███████╗███████╗░░░██║░░░
//░░░╚═╝░░░╚═╝░░╚═╝░░╚═╝╚══════╝╚══════╝╚══════╝░░░╚═╝░░░

if($text =="💳 Hamyonlar" && $tc == "private"){
    $card = $fuser["userfild"]["$from_id"]["card"];
    if($card == "false"){
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>🛑 Siz hali hamyon ulamagansiz\n\n🔆 Pul yechishingiz uchun hamyon ulashingiz kerak</b>",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
            [
['text'=>"💎 USDT TRC20 Hamyon ulash",'callback_data'=>'usdt']
            ],
            [
['text'=>"💳 BANK KARTA ulash",'callback_data'=>'card']
            ],
            ]
     ])
    ]);
    }else{
        if(strlen("$card")==16){$ctx="<b>💳 BANK KARTA:</b> <code>$card</code>"; $usd=" biriktirish"; $ca="ni o'zgartirish";}
        else{$ctx="<b>💎 USDT TRC20:</b> <code>$card</code>"; $ca=" biriktirish"; $usd="ni o'zgartirish";}
        bot('sendmessage',[
            'chat_id'=>$chat_id,
            'text'=>"<b>📮 Siz hisobingizga hamyon biriktirgansiz</b>

$ctx

<b>📌 Balansingizdan yechiladigan mablag' shu hisob raqamga tushadi</b>",
            "parse_mode"=>"html",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                [
    ['text'=>"💎 USDT TRC20 Hamyon$usd ",'callback_data'=>'usdt']
                ],
                [
    ['text'=>"💳 BANK KARTA$ca",'callback_data'=>'card']
                ],
                ]
         ])
        ]);
    }
    exit();
}


if($cdata =="usdt"){
    bot('editMessageText',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
        'text'=>"<b>💎 USDT TRC20 Hamyoningizni kiriting: nMisol uchun:</b> <code>TRCs96DPeRUMCxux2UqAabnaEdjEgg5P5h</code>",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[[['text'=>"⬅️ Bekor qilish",'callback_data'=>'back']],]
     ])
    ]);
    $cuser = json_decode(file_get_contents("users/$fromid/data.json"),true);
    $cuser["userfild"]["$fromid"]["step"]="usdt";
    $cuser = json_encode($cuser,true);
    file_put_contents("users/$fromid/data.json",$cuser);	
}

if($fuser["userfild"]["$from_id"]["step"] =="usdt"){
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>✅ USDT TRC20 Hamyon biriktirildi</b>",
        "parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["card"]="$text";
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);	
}


if($cdata =="card"){
    bot('editMessageText',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
        'text'=>"<b>💳 BANK KARTA raqamingizni kiriting: \nMisol uchun: </b> <code>9860000000000000</code>",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[[['text'=>"⬅️ Bekor qilish",'callback_data'=>'back']],]
     ])
    ]);
    $cuser = json_decode(file_get_contents("users/$fromid/data.json"),true);
    $cuser["userfild"]["$fromid"]["step"]="card";
    $cuser = json_encode($cuser,true);
    file_put_contents("users/$fromid/data.json",$cuser);		
}

if($fuser["userfild"]["$from_id"]["step"] =="card"){
    $text = str_replace(" ","", $text);
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>✅ Bank kartasi biriktirildi</b>",
        "parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser["userfild"]["$from_id"]["card"]="$text";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);	
    exit();
}

//█▀▀ █▄░█ █▀▄   █░█░█ ▄▀█ █░░ █░░ █▀▀ ▀█▀
//██▄ █░▀█ █▄▀   ▀▄▀▄▀ █▀█ █▄▄ █▄▄ ██▄ ░█░







if($text =="📄 To'lovlar" && $tc == "private"){
    $ref = $data["bot"]['ref'];
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>💸 To'lovlar tarixi haqidagi ma'lumotni olish uchun 
        
https://t.me/HistoryOfPaymentsAvolon

🔗 kanalga kirib, kuzatishingiz mumkin</b>",
    "parse_mode"=>"html",    
    ]);
    exit();
}



if($text =="🖇 Referal" && $tc == "private" ){
    $ref = $data["bot"]['ref'];
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>💸 Pul ishlashning boshqa usuli bilan tanishing
        
https://t.me/$bot?start=$from_id

🔗 Yuqoridagi silkani do'stlaringiz va gruhlarga tarqating va har bir botimizga kirgan foydalanuvchi hisobini to'ldirganda $ref foiz sizga bonus beriladi</b>",
    "parse_mode"=>"html",    
    ]);
    exit();
}

if($text =="📱 Profil" && $tc == "private"){
    $sum = $fuser["userfild"]["$from_id"]["sum"];
    $referal = $fuser["userfild"]["$from_id"]["referal"];
    $vip = $fuser["userfild"]["$from_id"]["VIP"]; $vname = $vip['name']; $vtask = $vip['task'];
    $vtx = "Sotib olmagansiz ❌";
    if($vip["status"] != "false"){
        $vtx = "Sotib olingan ✅
💠 Tarif nomi: <i>$vname</i>
〽️ Vazifalarim: $vtask ta / kuniga";
    }
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>❄️ Profil Malumotlaringiz 14uP 

🆔 raqamingiz: <code>$from_id</code>
💰 Hisobingizda: <code>$sum</code> so'm
🖇 Referallaringiz: <code>$referal</code> ta

⚜️ VIP: $vtx</b>",
"parse_mode"=>"html",
    ]);
    exit();
}

if($text =="💰 Balans" && $tc == "private"){
    $sum = $fuser["userfild"]["$from_id"]["sum"];
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>💰 Sizning hisobingizda <code>$sum</code> so'm mavjud</b>",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[[['text'=>"💸 Pul yechish",'callback_data'=>'withdraw']],]
     ])
    ]);
    exit();
}




//░██╗░░░░░░░██╗██╗████████╗██╗░░██╗██████╗░██████╗░░█████╗░░██╗░░░░░░░██╗
//░██║░░██╗░░██║██║╚══██╔══╝██║░░██║██╔══██╗██╔══██╗██╔══██╗░██║░░██╗░░██║
//░╚██╗████╗██╔╝██║░░░██║░░░███████║██║░░██║██████╔╝███████║░╚██╗████╗██╔╝
//░░████╔═████║░██║░░░██║░░░██╔══██║██║░░██║██╔══██╗██╔══██║░░████╔═████║░
//░░╚██╔╝░╚██╔╝░██║░░░██║░░░██║░░██║██████╔╝██║░░██║██║░░██║░░╚██╔╝░╚██╔╝░
//░░░╚═╝░░░╚═╝░░╚═╝░░░╚═╝░░░╚═╝░░╚═╝╚═════╝░╚═╝░░╚═╝╚═╝░░╚═╝░░░╚═╝░░░╚═╝░░




if($cdata =="withdraw"){
	$cuser = json_decode(file_get_contents("users/$fromid/data.json"),true);
	$sum = $cuser["userfild"]["$fromid"]["sum"];
	$minsum = $data['bot']['minimal'];
	if($cuser["userfild"]["$fromid"]["card"] == "false"){
		bot('answerCallbackQuery',[
            'callback_query_id'=>$qid,
            'text'=>"🔷 Siz hali hamyon ulamagansiz",
            'show_alert' => 1,
        ]);
        exit();
    }elseif($cuser["userfild"]["$fromid"]["wdate"] == $sana){
        bot('answerCallbackQuery',[
            'callback_query_id'=>$qid,
            'text'=>"🔷 Bir kunda bir marta pul yechish mumkin",
            'show_alert' => 1,
        ]);
        exit();
	}else{
		if( $minsum <= $sum){
			bot('deletemessage',[
				'chat_id'=>$cid2,
				'message_id'=>$mid2,
			]);
			bot('sendmessage',[
				'chat_id'=>$cid2,
				'text'=>"<b>💰 Qancha pul yechib olmoqchisiz: </b> \n\n🔸Hozir hisobingizda: $sum so'm mavjud\n🔆Minimal pul chiqarish: $minsum",
			"parse_mode"=>"html",   
			]);
			$cuser["userfild"]["$fromid"]["step"] = "withdraw";
			$cuser = json_encode($cuser,true);
			file_put_contents("users/$fromid/data.json",$cuser);
            exit();
		}else{
			bot('answerCallbackQuery',[
				'callback_query_id'=>$qid,
				'text'=>"💁‍♂️ Pul yechish uchun minimal mablag' $minsum so'm",
				'show_alert' => 1,
			]);
            exit();
		}
	}
}



if($fuser["userfild"]["$from_id"]["step"]=="withdraw" && $text != "⬅️ Back" && is_numeric($text) ){	
	$fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
	$sum = $fuser["userfild"]["$from_id"]["sum"];
	$card = $fuser["userfild"]["$from_id"]["card"];
	if(strlen($card)==16){$htx = "💳 Bank kartasi: $card";}else{$htx = "💎 USDT TRC20 Hamyon: $card";}
	$minsum = $data['bot']['minimal'];
	if($text > $sum){
		bot('sendmessage',[
			'chat_id'=>$chat_id,
			'text'=>"<b>💰 Hisobingizda $text so'm mablag' mavjud emas</b>",
		"parse_mode"=>"html",   
		]);
		$fuser["userfild"]["$from_id"]["step"] = "none";
		$fuser = json_encode($fuser,true);
		file_put_contents("users/$from_id/data.json",$fuser);
        exit();
	}else{
		if( $minsum <= $text){
			bot('sendmessage',[
				'chat_id'=>$chat_id,
				'text'=>"<b>❇️ Arizangiz qabul qilindi</b> \n\n Adminlar 24 soat ichida javob xabarini berishadi",
			"parse_mode"=>"html",   
			]);
			bot('sendmessage',[
				'chat_id'=>$data['bot']['payhisch'],
				'text'=>"<b>💸 Pul yechib olmoqchi
				
🆔 raqami: $from_id
❤️ ISMI: <a href='tg://user?id=$from_id'>$name</a>
💰 So'ralmoqda: $text
$htx 
		
✅ To'lovni amalga oshirib To'lov qilindi tugmasini bosing</b>",
			"parse_mode"=>"html",   
			'reply_markup'=>json_encode([
				'inline_keyboard'=>[[['text'=>"✅ To'lov qilindi",'callback_data'=>"wizd=$from_id=$text"]],[['text'=>"❌ Bekor qilish",'callback_data'=>"cancelwit=$from_id=$text"]]]
			])
			]);
			$fuser["userfild"]["$from_id"]["step"] = "none";
			$fuser["userfild"]["$from_id"]["sum"] = $sum-$text;
            $fuser["userfild"]["$from_id"]["wdate"] = $sana;
			$fuser = json_encode($fuser,true);
			file_put_contents("users/$from_id/data.json",$fuser);
            exit();
		}else{
			bot('sendmessage',[
				'chat_id'=>$chat_id,
				'text'=>"<b>💁‍♂️ Pul yechish uchun minimal mablag' $minsum so'm </b>",
                "parse_mode"=>"html",   
			]);
			$fuser["userfild"]["$from_id"]["step"] = "none";
			$fuser = json_encode($fuser,true);
			file_put_contents("users/$from_id/data.json",$fuser);
            exit();
		}
	}
}


if((stripos($cdata,"wizd=")!==false)){	
	if($fromid == $admin){
		$data = json_decode(file_get_contents("data/data.json"),true);
		$cdatas = explode('=', $cdata);
		$tid = $cdatas[1]; $ssum = $cdatas[2];
		$duser = json_decode(file_get_contents("users/$tid/data.json"),true);
		$card = $duser["userfild"]["$tid"]["card"];
		if(strlen($card)==16){$htx = "💳 Bank kartasi: $card";}else{$htx = "💎 USDT TRC20 Hamyon: $card";}
		bot('answerCallbackQuery',[
				'callback_query_id'=>$qid,
				'text'=>"💰 To'lov tastiqlandi",
				'show_alert' => 1,
			]);
		 
		bot('sendmessage',[
			'chat_id'=>$tid,
			'text'=>"<b> 💰 Sizning pul yechishga bergan arizangiz tastiqlandi\n\n❇️ To'lov qilindi 24 soat ichida hamyoningizga tushadi</b>",
			"parse_mode"=>"html",
		]);
		bot('deletemessage',[
			'chat_id'=>$cid2,
			'message_id'=>$mid2,
		]);
		bot('sendMessage',[
			'chat_id'=>$data["bot"]['paych'],
			'text'=>"<b>🟡 Pul yechib oldi
			
🆔 raqami: $tid
💰 Summa : $ssum so'm
$htx
	
	✅ Tastiqlandi</b>",
		"parse_mode"=>"html",   
		]);
        exit();
	}else{
		bot('answerCallbackQuery',[
				'callback_query_id'=>$qid,
				'text'=>"😊 Siz admin boshqaradigan tugmalardan foydalana olmaysiz",
				'show_alert' => 1,
			]);
            exit();
		}
		}
		
if((stripos($cdata,"cancelwit=")!==false)){	
	if($fromid == $admin){
		$data = json_decode(file_get_contents("data/data.json"),true);
		$cdatas = explode('=', $cdata);
		$tid = $cdatas[1]; $ssum = $cdatas[2];
		bot('answerCallbackQuery',[
				'callback_query_id'=>$qid,
				'text'=>"💔 Pul yechish so'rovi bekor qilindi",
				'show_alert' => 1,
			]);
		bot('sendmessage',[
			'chat_id'=>$tid,
			'text'=>"<b> $cdatas 💔 Pul yechishga bergan arizangiz bekor qilindi</b>",
			"parse_mode"=>"html",
		]);
		bot('deletemessage',[
			'chat_id'=>$cid2,
			'message_id'=>$mid2,
		]);
	exit();
	}else{
		bot('answerCallbackQuery',[
				'callback_query_id'=>$qid,
				'text'=>"😊 Siz admin boshqaradigan tugmalardan foydalana olmaysiz",
				'show_alert' => 1,
			]);
		}
	}


//█▀▀ █▄░█ █▀▄   █░█░█ █ ▀█▀ █░█ █▀▄ █▀█ ▄▀█ █░█░█
//██▄ █░▀█ █▄▀   ▀▄▀▄▀ █ ░█░ █▀█ █▄▀ █▀▄ █▀█ ▀▄▀▄▀











//██╗░░░██╗██╗██████╗░
//██║░░░██║██║██╔══██╗
//╚██╗░██╔╝██║██████╔╝
//░╚████╔╝░██║██╔═══╝░
//░░╚██╔╝░░██║██║░░░░░
//░░░╚═╝░░░╚═╝╚═╝░░░░░


if($text =="⚜️ VIP" && $tc == "private"){	
    $vipp = $data["vip"];
    $array = array("inline_keyboard");
    $c = count($vipp);
    $n = 0; $p = 0;
    for($i=0;$i<$c;$i+=3){
        $k=$i+1; $h=$k+1;

        $array['inline_keyboard']["$n"][0]['text'] = $vipp["$i"]['name'];
        $array['inline_keyboard']["$n"][0]['callback_data'] = "vip=$i"; $p++;

        if( $c > $p){
        $array['inline_keyboard']["$n"][1]['text'] = $vipp["$k"]['name'];
        $array['inline_keyboard']["$n"][1]['callback_data'] = "vip=$k"; $p++;
        }

        if( $c > $p ){
        $array['inline_keyboard']["$n"][2]['text'] = $vipp["$h"]['name'];
        $array['inline_keyboard']["$n"][2]['callback_data'] = "vip=$h"; $p++;
        }
        $n++;
    }

    bot('sendPhoto',[
        'chat_id'=>$chat_id,
        'photo'=>"https://t.me/", #rasm qo'ying
        'caption'=>"<b> 🔗 Kerakli VIP tarifni tanlang va u haqida ma'lumot oling ! \n\n ⚠️ Tarif sizni kelajakda qancha ko'p topishingiz uchun asosiy ustundir</b>",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode($array),
    ]);
    exit();
}


if($cdata =="vips"){
    bot('deleteMessage',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
    ]);	
    $vipp = $data["vip"];
    $array = array("inline_keyboard");
    $c = count($vipp);
    $n = 0; $p = 0;
    for($i=0;$i<$c;$i+=3){
        $k=$i+1; $h=$k+1;

        $array['inline_keyboard']["$n"][0]['text'] = $vipp["$i"]['name'];
        $array['inline_keyboard']["$n"][0]['callback_data'] = "vip=$i"; $p++;

        if( $c > $p){
        $array['inline_keyboard']["$n"][1]['text'] = $vipp["$k"]['name'];
        $array['inline_keyboard']["$n"][1]['callback_data'] = "vip=$k"; $p++;
        }

        if( $c > $p ){
        $array['inline_keyboard']["$n"][2]['text'] = $vipp["$h"]['name'];
        $array['inline_keyboard']["$n"][2]['callback_data'] = "vip=$h"; $p++;
        }
        $n++;
    }

    bot('sendPhoto',[
        'chat_id'=>$cid2,
        'photo'=>"https://t.me/", #rasm qo'ying
        'caption'=>"<b> 🔗 Kerakli VIP tarifni tanlang va u haqida ma'lumot oling ! \n\n ⚠️ Tarif sizni kelajakda qancha ko'p topishingiz uchun asosiy ustundir</b>",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode($array),
    ]);
    exit();
}


if((stripos($cdata,"vip=")!==false)){	
    $data = json_decode(file_get_contents("data/data.json"),true);
    $cdatas = explode('=', $cdata);
    $vipp = $data["vip"][$cdatas[1]];
    $vname = $vipp['name']; $vtask = $vipp['task']; $vsum = $vipp['sum']; $income = $vipp['income'];
    bot('deleteMessage',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
    ]);	
    if($cuser["userfild"]["$fromid"]["VIP"]['status'] == "false"){
        $narxi = "💰 Narxi: <code>$vsum</code> so'm";
    }else{
        $sn = $vsum - $cuser["userfild"]["$fromid"]["VIP"]['old'];
        if($sn < 0){
            $narxi = "💰 Narxi: <code>$vsum</code> so'm";
        }else{
        $narxi = "💰 Narxi: <s>$vsum so'm</s>
                            $sn so'm";
        }
    }
    bot('sendmessage',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
        'text'=>"<b>💎 TARIF HAQIDA MA'LUMOT

        $vname
        🛒 Vazifasi: <code>$vtask</code> ta / kuniga
        💸 Kunlik daromad: <code>$income</code> so'm
        $narxi
        
💡 Agar tarif sizga maqul bo'lsa quyidagi sotib olish tugmasini bosing</b>",
        "parse_mode"=>"html",
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[[['text'=>"💰 Sotib olish 💸",'callback_data'=>'vippay='.$cdatas[1]]],[['text'=>"🔸 Ortga qaytish",'callback_data'=>'vips']]]
     ])
    ]);
    exit();
}


if((stripos($cdata,"vippay=")!==false)){	
    $data = json_decode(file_get_contents("data/data.json"),true);
    $sum = $cuser["userfild"]["$fromid"]["sum"];
    $fvip = $cuser["userfild"]["$fromid"]["VIP"];
    $cdatas = explode('=', $cdata);
    $vipp = $data["vip"][$cdatas[1]];
    $vname = $vipp['name']; $vtask = $vipp['task']; $vsum = $vipp['sum']; $vincome = $vipp['income'];
    $refid = $cuser["userfild"]["$fromid"]["refralid"];
    
    if($fvip['status'] == $cdatas[1]){
        bot('answerCallbackQuery',[
            'callback_query_id'=>$qid,
            'text'=>"😊 Siz shundoq ham shu tarifdasiz",
            'show_alert' => 1,
        ]);
        exit();
    }else{
        if($cuser["userfild"]["$fromid"]["VIP"]['status'] != "false"){
            $sn = $vsum - $cuser["userfild"]["$fromid"]["VIP"]['old'];
        }else{
            $sn = $vsum;
        }
        if($sum >= $sn){
            if($sn < 0){
                bot('answerCallbackQuery',[
                    'callback_query_id'=>$qid,
                    'text'=>"Uzur bu tarif sizning darajangizga to'g'ri kelmaydi 🙁",
                    'show_alert' => 1,
                ]);
                exit();
            }else{
            bot('answerCallbackQuery',[
                'callback_query_id'=>$qid,
                'text'=>"🎉 Tabriklaymiz! Yangi $vname tarifiga muvaffaqiyatli o'tdingiz",
                'show_alert' => 1,
            ]);
            bot('deleteMessage',[
                'chat_id'=>$cid2,
                'message_id'=>$mid2,
            ]);	
            bot('sendMessage',[
                'chat_id'=>$cid2,
                'text'=>"🎉",
                'reply_markup'=>$menu,
            ]);	

            bot('sendMessage',[
                'chat_id'=>$data['bot']['vipch'],
                'text'=>"<b>🚀 VIP TARIF SOTIB OLDI</b>
                
                🙋‍♂️ Foydalanuvchi ismi: <a href='tg://user?id=$fromid'>$callname</a>
                🆔 ID raqami: <code>$fromid</code>
                📃 Tarif nomi: $vname
                💰 Tarif narxi: $vsum
                💸 Kunlik daromadi: $vincome",
                'parse_mode'=>'html',
            ]);	
            
            $sum = $sum - $sn;
            $cuser["userfild"]["$fromid"]["sum"] = $sum;
            $cuser["userfild"]["$fromid"]["VIP"]['status'] = $cdatas[1];
            $cuser["userfild"]["$fromid"]["VIP"]['name'] = $vname;
            $cuser["userfild"]["$fromid"]["VIP"]['task'] = $vtask;
            $cuser["userfild"]["$fromid"]["VIP"]['sum'] = $vsum;
            $cuser["userfild"]["$fromid"]["VIP"]['old'] = $vsum;
            $cuser["userfild"]["$fromid"]["VIP"]['bonus'] = $data["bonus"];
            $cuser = json_encode($cuser,true);
            file_put_contents("users/$fromid/data.json",$cuser);
            
            if($refid != null){
                    $cuser = json_decode(file_get_contents("users/$fromid/data.json"),true);
                    $ruser = json_decode(file_get_contents("users/$refid/data.json"),true);
                    if($ruser["userfild"]["$refid"]["VIP"]["status"] != "false" ){
                        $vrid = $ruser["userfild"]["$refid"]["VIP"]["status"];
                        $rbonus = $data["vip"][$vrid]['bonus'];
                        $cbonus = $vipp['bonus'];
                        $sum = $ruser["userfild"]["$refid"]["sum"];
                        $bn = 0;
                        if($rbonus >= $cbonus){
                            $bn = $cbonus;
                        }else{
                            $bn = $rbonus;
                        }
                        $ruser["userfild"]["$refid"]["sum"] = $sum + $bn;
                        $ruser = json_encode($ruser,true);
                        file_put_contents("users/$refid/data.json",$ruser);
                        bot('sendMessage',[
                            'chat_id'=>$refid,
                            'text'=>"🎉 <b> Do'stingiz $vname Tarif tanladi va Sizga $bn so'm bonus berildi</b>",
                            'parse_mode'=>"html",
                            'reply_markup'=>$menu,
                        ]);	
                    }
                    }
            exit();
            }
    }else{
            bot('answerCallbackQuery',[
                'callback_query_id'=>$qid,
                'text'=>"💸 Sizning hisobingda yetarli mablag' mavjud emas",
                'show_alert' => 1,
            ]);
            exit();
        }
    }
}


//█▀▀ █▄░█ █▀▄   █░█ █ █▀█
//██▄ █░▀█ █▄▀   ▀▄▀ █ █▀▀











//████████╗░█████╗░░██████╗██╗░░██╗
//╚══██╔══╝██╔══██╗██╔════╝██║░██╔╝
//░░░██║░░░███████║╚█████╗░█████═╝░
//░░░██║░░░██╔══██║░╚═══██╗██╔═██╗░
//░░░██║░░░██║░░██║██████╔╝██║░╚██╗
//░░░╚═╝░░░╚═╝░░╚═╝╚═════╝░╚═╝░░╚═╝




if($text =="🚀 Vazifalar" && $tc == "private"  && joinchat($from_id)){
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
	$vips = $fuser["userfild"]["$from_id"]["VIP"]["status"];
	$sdata = $fuser["userfild"]["$from_id"]["task"]['data'];
	if($vips == "false"){
		bot('sendmessage',[
			'chat_id'=>$chat_id,
			'text'=>"<b>☑️ Vazifa bajarib pul ishlash uchun VIP tarif tanlashingiz kerak</b>",
		"parse_mode"=>"html", 
		]);
		exit();
	}else{
		if($sdata == $sana){
			bot('sendmessage',[
				'chat_id'=>$chat_id,
				'text'=>"<b>🪫 Bugungi vazifani bajarib bo'ldingiz</b>",
			"parse_mode"=>"html", 
			]);
			exit();
		}else{
            $income = $data['vip'][$vips]["income"];
            bot('sendphoto',[
                'chat_id'=>$chat_id,
                'photo'=>"https://t.me/", #rasm qo'ying
                'caption'=>"<b>🔋 Siz Avolon bilan kuniga pul topasiz
                
🏜 Sahroda elektr energiyasi ishlab chiqarish
💰 Narxi: $income so'm

✅ Quyidagi Bonus olish tugmasini bosing</b>",
            "parse_mode"=>"html", 
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[[['text'=>"✅ Bonusni Olish",'callback_data'=>'confirn']]]
         ])
        ]);
        exit();
	}
}
}




if($cdata == "confirn"  && joinchat($fromid)){	
    $cuser = json_decode(file_get_contents("users/$fromid/data.json"),true);
    $refid = $cuser["userfild"]["$fromid"]["refralid"];
	$vips = $cuser["userfild"]["$fromid"]["VIP"]["status"];
	$sdata = $cuser["userfild"]["$fromid"]["task"]['data'];
	if($vips == "false"){
		bot('answerCallbackQuery',[
           'callback_query_id'=>$qid,
           'text'=>"☑️ Vazifa bajarib pul ishlash uchun VIP tarif tanlashingiz kerak",
           'show_alert' => 1,
        ]);
        bot('deletemessage',[
           'chat_id'=>$cid2,
           'message_id'=>$mid2,
        ]);
		exit();
	}else{
		if($sdata == $sana){
            bot('answerCallbackQuery',[
                'callback_query_id'=>$qid,
                'text'=>"🪫 Bugungi vazifani bajarib bo'ldingiz",
                'show_alert' => 1,
            ]);
            bot('deletemessage',[
                'chat_id'=>$cid2,
                'message_id'=>$mid2,
            ]);
            exit();
		}else{
    $data = json_decode(file_get_contents("data/data.json"),true);
    $vips = $cuser["userfild"]["$fromid"]["VIP"]["status"];
    $ssum = $data['vip'][$vips]['income'];
    bot('answerCallbackQuery',[
            'callback_query_id'=>$qid,
            'text'=>"✅ Vazifa muvaffaqiyatli bajarildi! Hisobingizga $ssum so'm qo'shildi",
            'show_alert' => 1,
        ]);
    bot('deletemessage',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
    ]);
    $cuser = json_decode(file_get_contents("users/$fromid/data.json"),true);
    $sum = $cuser["userfild"]["$fromid"]["sum"];
    $cuser["userfild"]["$fromid"]["sum"]= $sum + $ssum;
    $cuser["userfild"]["$fromid"]["task"]["data"]= $sana;
    $cuser = json_encode($cuser,true);
    file_put_contents("users/$fromid/data.json",$cuser);

    if($refid != null){
    $ruser = json_decode(file_get_contents("users/$refid/data.json"),true);
    $sum = $ruser["userfild"]["$refid"]["sum"];
    $ruser["userfild"]["$refid"]["sum"]= $sum + 200; #ref sum 200  so'm
    $ruser = json_encode($ruser,true);
    file_put_contents("users/$refid/data.json",$ruser);
    
    bot('sendmessage',[
		'chat_id'=>$refid,
		'text'=>"<b>☑️ Sizning do'stingiz kunlik vazifani bajardi va sizga 200 so'm bonus berildi</b>",
		"parse_mode"=>"html", 
		]);
    }
    exit();
}
}
}

//█▀▀ █▄░█ █▀▄   ▀█▀ ▄▀█ █▀ █▄▀
//██▄ █░▀█ █▄▀   ░█░ █▀█ ▄█ █░█







//██████╗░███████╗██████╗░░█████╗░░██████╗██╗████████╗
//██╔══██╗██╔════╝██╔══██╗██╔══██╗██╔════╝██║╚══██╔══╝
//██║░░██║█████╗░░██████╔╝██║░░██║╚█████╗░██║░░░██║░░░
//██║░░██║██╔══╝░░██╔═══╝░██║░░██║░╚═══██╗██║░░░██║░░░
//██████╔╝███████╗██║░░░░░╚█████╔╝██████╔╝██║░░░██║░░░
//╚═════╝░╚══════╝╚═╝░░░░░░╚════╝░╚═════╝░╚═╝░░░╚═╝░░░

if($text =="📥 Balans To'ldirish" && $tc == "private"){
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>🟢 Hisobingizni to'ldirmoqchiisiz</b>
        
        ♻️VIP toʻldirish uchun Avolon agentlar roʻyhati:     
        🔸 Quyidagi agent karta raqamlariga VIP da ko'rsatilgan summani yuboring.
        📃 To'lov kvitansiyasini screenshot qilib bizga jo'natishingiz kerak.
        
        1-Agent 
        2-Agent
        
        To'lov tizimida muammo bo'lsa adminga yozing:
       
        
        ✅ Yuqoridagi shartlarga rozi bo'lsangiz quyidagi ❇️ Roziman tugmasini bosing ",
    "parse_mode"=>"html", 
    'reply_markup'=>json_encode([
        'inline_keyboard'=>[
        [['text'=>"❇️ Roziman",'callback_data'=>"deposit"]],
    ]
    ]) 
    ]);
    exit();
}

if($cdata =="deposit"){
    bot('deletemessage',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
    ]);
    bot('sendmessage',[
        'chat_id'=>$cid2,
        'text'=>"<b>💰 Hisobingizni qanchaga to'ldirmoqchisiz: </b>
        

🔺 Iltimos so'mda kiriting faqat raqam bilan ifodalang:
    Misol uchun: 150000",
    "parse_mode"=>"html",   
    ]);
    $cuser["userfild"]["$fromid"]["step"] = "deposit";
    $cuser = json_encode($cuser,true);
    file_put_contents("users/$fromid/data.json",$cuser);
    exit();
}


if($fuser["userfild"]["$from_id"]["step"]=="deposit" && $text != "⬅️ Back" && is_numeric($text) ){	
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"
🔷 Endi asosiy ish qoldi! Roʻyhatdagi agentlarga yozib $text so'm o'tkazing

1-Agent 

 
📃 O'tkazgan to'lov kvitansiyangizni bizga yuboring. 
⚠️Toʻlov kvitansiyasini qalbakilashtirishga urunmang.

To'lov kvitansiyasini yuborishingiz mumkun ",
"parse_mode"=>"html",
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="deposit_photo";
    $fuser["userfild"]["$from_id"]["step_sum"]="$text";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
    exit();
}


if($fuser["userfild"]["$from_id"]["step"]=="deposit_photo" && $photo){	
$data = json_decode(file_get_contents("data/data.json"),true);
    $photo_id = $photo[0]->file_id;
    $ssum = $fuser["userfild"]["$from_id"]["step_sum"];
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>✅ Barchasi alo darajada bajarildi 

♻️ Adminlarimiz tekshirib chiqib 10 daqiqa ichida hisobingizni to'ldirishadi </b>",
"parse_mode"=>"html",
    ]);
    bot('sendPhoto',[
        'chat_id'=>$data['bot']['payhisch'],
        'photo'=>$photo_id,
        'caption'=>"<b>🟢 Hisobini to'ldirmoqchi
        
        🆔 raqami: $from_id
        ❤️ ISMI: <a href='tg://user?id=$from_id'>$name</a>
        💰 So'ralmoqda: $ssum

✅ Chekni tekshiring agar hammasi to'g'ri bo'lsa Tastiqlang yoki qo'lda to'ldiring</b>",
    "parse_mode"=>"html",   
    'reply_markup'=>json_encode([
        'inline_keyboard'=>[[['text'=>"✅ Tastiqlayman",'callback_data'=>"tastiq=$from_id=$ssum"]],[['text'=>"🔸 Rujnoy qo'shaman",'callback_data'=>"ruchnoy=$from_id=$ssum"]],[['text'=>"❌ Bekor qilish",'callback_data'=>"cancel=$from_id=$ssum"]]]
    ])
    ]);
    $fuser = json_decode(file_get_contents("users/$from_id/data.json"),true);
    $fuser["userfild"]["$from_id"]["step"]="none";
    $fuser = json_encode($fuser,true);
    file_put_contents("users/$from_id/data.json",$fuser);
    exit();
}

if((stripos($cdata,"tastiq=")!==false)){	
if($fromid == $admin){
    $data = json_decode(file_get_contents("data/data.json"),true);
    $cdatas = explode('=', $cdata);
    $tid = $cdatas[1]; $ssum = $cdatas[2];
    $duser = json_decode(file_get_contents("users/$tid/data.json"),true);
    $rid = $duser["userfild"]["$tid"]["refralid"];
    $tsum = $duser["userfild"]["$tid"]["sum"] + $ssum;
    $duser["userfild"]["$tid"]["sum"] = $tsum;
    $duser = json_encode($duser,true);
    file_put_contents("users/$tid/data.json",$duser);
    bot('answerCallbackQuery',[
            'callback_query_id'=>$qid,
            'text'=>"💰 Foydalanuvchi balansi to'ldirildi",
            'show_alert' => 1,
        ]);
     
    bot('sendmessage',[
        'chat_id'=>$tid,
        'text'=>"<b> 💰 Hisobingiz admin tomonidan <code>$ssum</code> so'mga to'ldirdi\n\n🟠 Hozirgi balansingiz: <code>$tsum</code> so'm</b>",
        "parse_mode"=>"html",
    ]);
    if($rid){
        $rsum = $ssum / 100 * $data['bot']['ref'];
        bot('sendmessage',[
            'chat_id'=>$rid,
            'text'=>"<b>🔆 Siz botimizga taklif qilgan bir do'stingiz hisobini to'ldirdi\n\n💰 Sizning hisobingizga +$rsum so'm qo'shib berildi </b>",
            "parse_mode"=>"html",
        ]);
        $ruser = json_decode(file_get_contents("users/$rid/data.json"),true);
        $rsum = $ruser["userfild"]["$rid"]["sum"] + $rsum;
        $ruser["userfild"]["$rid"]["sum"] = $rsum;
        $ruser = json_encode($ruser,true);
        file_put_contents("users/$rid/data.json",$ruser);
    }
    bot('deletemessage',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
    ]);
    bot('sendMessage',[
        'chat_id'=>$data["bot"]['paych'],
        'text'=>"<b>🟢 Hisobini to'ldirdi
        
🆔 raqami: $tid
💰 Summa : $ssum so'm

✅ Tastiqlandi</b>",
    "parse_mode"=>"html",   
    ]);
    exit();
}else{
	bot('answerCallbackQuery',[
            'callback_query_id'=>$qid,
            'text'=>"😊 Siz admin boshqaradigan tugmalardan foydalana olmaysiz",
            'show_alert' => 1,
        ]);
    exit();
	}
}
	
	
	if((stripos($cdata,"ruchnoy=")!==false)){	
if($fromid == $admin){
    $data = json_decode(file_get_contents("data/data.json"),true);
    $cdatas = explode('=', $cdata);
    $tid = $cdatas[1]; $sum = $cdatas[2];
    bot('answerCallbackQuery',[
            'callback_query_id'=>$qid,
            'text'=>"💎 Hisobni to'ldirish uchun barcha malumotlar lichkangizga yuborildi",
            'show_alert' => 1,
        ]);
    bot('sendmessage',[
        'chat_id'=>$cid2,
        'text'=>"<b> 💰 Hisobini to'ldirishingiz kerak

🆔 Raqami: <code>$tid</code>
💸 O'tkazish summasi: $sum </b>",
        "parse_mode"=>"html",
    ]);
    bot('deletemessage',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
    ]);
}else{
	bot('answerCallbackQuery',[
            'callback_query_id'=>$qid,
            'text'=>"😊 Siz admin boshqaradigan tugmalardan foydalana olmaysiz",
            'show_alert' => 1,
        ]);
	}
	exit();
}
	
if((stripos($cdata,"cancel=")!==false)){	
if($fromid == $admin){
    $data = json_decode(file_get_contents("data/data.json"),true);
    $cdatas = explode('=', $cdata);
    $tid = $cdatas[1]; $sum = $cdatas[2];
    bot('answerCallbackQuery',[
            'callback_query_id'=>$qid,
            'text'=>"💔 Hisobni to'ldirish bekor qilindi",
            'show_alert' => 1,
        ]);
    bot('sendmessage',[
        'chat_id'=>$tid,
        'text'=>"<b> 💔 Hisobingizni $sum so'mga to'ldirish haqidagi arizangiz admin tomonidan bekor qilindi</b>\nBunga sabab noo'rin chek yoki noto'g'ri to'lov summasi bo'lishi mumkin",
        "parse_mode"=>"html",
    ]);
    bot('deletemessage',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2,
    ]);

}else{
	bot('answerCallbackQuery',[
            'callback_query_id'=>$qid,
            'text'=>"😊 Siz admin boshqaradigan tugmalardan foydalana olmaysiz",
            'show_alert' => 1,
        ]);
	}
	exit();
}


//█▀▀ █▄░█ █▀▄   █▀▄ █▀▀ █▀█ █▀█ █▀ █ ▀█▀
//██▄ █░▀█ █▄▀   █▄▀ ██▄ █▀▀ █▄█ ▄█ █ ░█░