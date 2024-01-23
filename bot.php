<?php
ob_start();
define('API_KEY','6725286497:AAEC86GWoX-t4zLlZXojYwPGSNrSyznj5Vw'); //your bot token
$botim = "ChanallBots"; //chanel username
$admin = array("[*ADMIN*]","",""); //id
   function del($nomi){
   array_map('unlink', glob("$nomi"));
   }
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
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
$mid = $message->message_id;
$cid = $message->chat->id;
$tx = $message->text;
$photo = $message->photo;



if($tx=="/start"){
bot('sendmessage',[
'chat_id'=>$cid,
    'text'=>"• QRCODE maker
• sent that sentence you want put it in qr code.

like👇
/qr im here" ,
'parse_mode'=>'markdown',
]);
}



if($photo){
$file_id = $message->photo[0]->file_id;
$url = json_decode(file_get_contents('https://api.telegram.org/bot'.API_KEY.'/getFile?file_id='.$file_id),true);
$path=$url['result']['file_path'];
$file = 'https://api.telegram.org/file/bot'.API_KEY.'/'.$path;
$type = strtolower(strrchr($file,'.')); 
$type=str_replace('.','',$type);
if( ($type !== "png") and ($type !== "jpg")){
    bot('sendmessage',[
    'chat_id'=>$cid,
    'text'=>"" ,
]);
}
else{
$okey = file_put_contents("data/$cid.png",file_get_contents($file));
if($okey==false){
    bot('sendmessage',[
    'chat_id'=>$cid,
    'text'=>"" ,
]);
}else{
$url = "http://u3551.xvest1.ru/QR/data/$cid.png";
$api = json_decode(file_get_contents("https://api.qrserver.com/v1/read-qr-code/?fileurl=$url"));
$text=$api[0]->symbol[0]->data;
$error=$api[0]->symbol[0]->error;
if($error==null){
bot('sendmessage',[
'chat_id'=>$cid,
    'text'=>"🔣the sentence with QR CODE:⤵️

 `$text`

🎯By: @$botim" ,
'parse_mode'=>'markdown',
]);
}else{
bot('sendmessage',[
    'chat_id'=>$cid,
    'text'=>"the bot can't read the qr code it wrong sorry" ,
]);
}
}
}
}
if($tx=='/qr'){
bot('sendmessage',[
'chat_id'=>$cid,
    'text'=>"bot qr code maker

/qr hello world

sent it like that" ,
'parse_mode'=>'markdown',
]);
}
if(mb_stripos($tx,"/qr")!==false){ 
$ex=explode("/qr ",$tx); 
$text=$ex[1]; 
$api = array("http://qr-code.ir/api/qr-code?s=5&e=M&t=P&d=$text","http://api.qrserver.com/v1/create-qr-code/?data=$text"); 

bot('sendPhoto',[
'chat_id'=>$cid,
"photo"=>$api[0],
    'caption'=>"sent that sentence you want put it in qr code: *$text*" ,
'parse_mode'=>'markdown',
]);
}

