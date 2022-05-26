<?php
function SJ($file,$content){
	return file_put_contents($file,json_encode($content,JSON_PRETTY_PRINT));
}
if(!file_exists('admin.json')){
$token = readline('- token: ');
$id = readline('- id: ');
$save['info'] = [
'token'=>$token,
'id'=>$id
];
file_put_contents('admin.json',json_encode($save),64|128|256);
}
function save($array){
file_put_contents('admin.json',json_encode($array),64|128|256);
}
$token = json_decode(file_get_contents('admin.json'),true)['info']['token'];
$id = json_decode(file_get_contents('admin.json'),true)['info']['id'];
include 'index.php';
if($id == ""){
echo "Error Id";
}
try {
 $callback = function ($update, $bot) {
  global $id;
  if($update != null){
$message = $update->message;
$text = $message->text; 
$data = $update->callback_query->data; 
$user = $update->message->from->username; 
$user2 = $update->callback_query->from->username; 
$name = $update->message->from->first_name; 
$name2 = $update->callback_query->from->first_name; 
$message_id = $message->message_id;
$message_id2 = $update->callback_query->message->message_id; 
$chat_id = $message->chat->id; 
$chat_id2 = $update->callback_query->message->chat->id; 
$from_id = $message->from->id;
$from_id2 = $update->callback_query->message->from->id; 
$type = $update->message->chat->type;
$id = json_decode(file_get_contents('admin.json'),true)['info']['id'];
$JS1 = json_decode(file_get_contents('Light.json'),true);
if($text == '/start' && $from_id == $id){
	bot('sendMessage',[
		'chat_id'=>$chat_id,
		'text'=>"
اهلا بك في بوت
تحكم في حسابك
تطبيق : ( سينرز لوفو )
",
'reply_markup'=>json_encode([
	'inline_keyboard'=>[
		[['text'=>"تسجيل الدخول",'callback_data'=>'log']],
		[['text'=>"معلومات الحساب",'callback_data'=>'accinfo']],
		[['text'=>"رشق حساب",'callback_data'=>'order'],['text'=>"تحويل النقاط",'callback_data'=>'transfer']],
		[['text'=>"دعم الصانع ؟",'callback_data'=>'Support']],
		]
	    ])
		]);
}
if($data == 'back'){
bot('editMessageText',[
		'chat_id'=>$chat_id2,
		'message_id'=>$message_id2,
		'text'=>"
اهلا بك في بوت
تحكم في حسابك
تطبيق : ( سينرز لوفو )
",
'reply_markup'=>json_encode([
	'inline_keyboard'=>[
		[['text'=>"تسجيل الدخول",'callback_data'=>'log']],
		[['text'=>"معلومات الحساب",'callback_data'=>'accinfo']],
		[['text'=>"رشق حساب",'callback_data'=>'order'],['text'=>"تحويل النقاط",'callback_data'=>'transfer']],
		[['text'=>"دعم الصانع ؟",'callback_data'=>'Support']],
		]
	    ])
		]);
$JS1[$chat_id2]['command'] = null;
SJ('Light.json',$JS1);
}
if($data == 'log'){
	bot('editMessageText',[
	'chat_id'=>$chat_id2,
    'message_id'=>$message_id2,
    'text'=>"
قم الان ب ارسال اليوزر:الباسوورد
الخاص ب تطبيق سينرز
( ليس الخاص ب الانستكرام )
    ",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"الرجوع",'callback_data'=>'back']],
]
])
]);
$JS1[$chat_id2]['command'] = 'login';
SJ('Light.json',$JS1);
}
if($text != '/start' && $JS1[$chat_id]['command'] == 'login'){
$ex = explode(":",$text);
$login = Login($ex['0'],$ex['1']);
$token = json_decode($login,true)['token'];
if(json_decode($login,true)['message'] == 'Login Successfully'){
    $JS1[$chat_id]['token'] = $token;
    SJ('Light.json',$JS1);
	bot('sendMessage',[
		'chat_id'=>$chat_id,
		'text'=>"
تم تسجيل الدخول ب الحساب بنجاح
يمكنك الان استخدام البوت ب شكل كامل
		",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"الرجوع",'callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['command'] = null;
SJ('Light.json',$JS1);
}else{
	bot('sendMessage',[
		'chat_id'=>$chat_id,
		'text'=>"
عذراً الباسوورد ام اليوزر خطا
يرجى التحقق مره ثانيه من المعلومات
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"الرجوع",'callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['command'] = null;
SJ('Light.json',$JS1);
}
}
if($data == 'accinfo' && $JS1[$chat_id2]['token'] != null){
$token = $JS1[$chat_id2]['token'];
$getInfo = getInfo($token);
    $decode = json_decode($getInfo,true);
    $name = $decode['data']['name'];
    $username = $decode['data']['username'];
    $email = $decode['data']['email'];
    $coins = $decode['data']['coin'];
	bot('editMessageText',[
	'chat_id'=>$chat_id2,
    'message_id'=>$message_id2,
    'text'=> "
    اسم الحساب : $name
    يوزر الحساب : $username
    الايميل : $email
    عدد النقاط : $coins
    ",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"الرجوع",'callback_data'=>'back']],
]
])
]);
}elseif($data == 'accinfo' && $JS1[$chat_id2]['token'] == null){
bot('answerCallbackQuery', [
    'callback_query_id' => $update->callback_query->id,
    'text' =>"يجب تسجيل الدخول اولاً !️",
    'show_alert' => true,
  ]);
}
if($data == 'transfer' && $JS1[$chat_id2]['token'] != null){
	bot('editMessageText',[
	'chat_id'=>$chat_id2,
    'message_id'=>$message_id2,
    'text'=> "
	    حسناً الان قم ب ارسال المعلومات هكذا
	    العدد:الشخص الذي تريد التحويل اليه
	    مثل : 
610:wjwoxm
	    ",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"الرجوع",'callback_data'=>'back']],
]
])
]);
$JS1[$chat_id2]['command'] = 'transfer';
SJ('Light.json',$JS1);
}elseif($data == 'transfer' && $JS1[$chat_id2]['token'] == null){
bot('answerCallbackQuery', [
    'callback_query_id' => $update->callback_query->id,
    'text' =>"يجب تسجيل الدخول اولاً !️",
    'show_alert' => true,
]);
}
if($text != '/start' && $JS1[$chat_id]['command'] == 'transfer'){
$exp = explode(":",$text);
$exp1 = $exp['0'];
$exp2 = $exp['1'];
$JS1[$chat_id]['amount'] = $exp1;
SJ('Light.json',$JS1);
$JS1[$chat_id]['transferto'] = $exp2;
SJ('Light.json',$JS1);
	bot('sendMessage',[
	'chat_id'=>$chat_id,
    'text'=> "
لقد تم تحديد المعلومات
عدد النقاط الذي سيتم تحويله : $exp1
الشخص الذي سيتم تحويل اليه النقاط : $exp2
هل تريد تأكيد العملية ؟
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"التاكيد",'callback_data'=>'confirmtrans']],
[['text'=>"الرجوع",'callback_data'=>'back']],
]
])
]);
$JS1[$chat_id]['command'] = null;
SJ('Light.json',$JS1);
}
if($data == 'confirmtrans'){
$token = $JS1[$chat_id2]['token'];
$transferto = $JS1[$chat_id2]['transferto'];
$amount = $JS1[$chat_id2]['amount'];
$transfer = transCoin($token,$transferto,$amount);
if(json_decode($transfer,true)['message'] == 'Coin transition successfully'){
	bot('editMessageText',[
	'chat_id'=>$chat_id2,
    'message_id'=>$message_id2,
    'text'=>"
    تم تحويل النقاط بنجاح
    يوزر المستخدم : $transferto
    عدد النقاط : $amount
    ",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"الرجوع",'callback_data'=>'back']],
]
])
]);
}else{
bot('answerCallbackQuery', [
    'callback_query_id' => $update->callback_query->id,
    'text' =>"يوجد مشكله اثناء تحويل النقاط
 
    $transfer",
    'show_alert' => true,
  ]);
}
}
if($data == 'order'){
	bot('editMessageText',[
	'chat_id'=>$chat_id2,
    'message_id'=>$message_id2,
    'text'=>"
حسناً الان قم ب ارسال المعلومات كالتالي :
يوزر الحساب:العدد
مثل :
@el7q.q:100
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"الرجوع",'callback_data'=>'back']],
]
])
]);
$JS1[$chat_id2]['command'] = 'order';
SJ('Light.json',$JS1);
}
if($text != '/start' && $JS1[$chat_id]['command'] == 'order'){
$str = str_replace('@',null,$text);
$exp = explode(":",$str);
$exp1 = $exp['0']; // user
$exp2 = $exp['1']; // amountorder
$JS1[$chat_id]['user'] = $exp1;
SJ('Light.json',$JS1);
$JS1[$chat_id]['amountorder'] = $exp2;
SJ('Light.json',$JS1);
	bot('sendMessage',[
	'chat_id'=>$chat_id,
    'text'=>"
لقد تم تحديد المعلومات
عدد المتابعين الذي سيتم ارساله : $exp2
يوزر الحساب الذي سيتم رشقه : $exp1
    ",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"التاكيد",'callback_data'=>'confirmorder']],
[['text'=>"الرجوع",'callback_data'=>'back']],
]
])
]);
}
if($data == 'confirmorder'){
$user = $JS1[$chat_id2]['user'];
$amountorder = $JS1[$chat_id2]['amountorder'];
$token = $JS1[$chat_id2]['token'];
$contents = json_decode(file_get_contents("https://soud.me/api/Instagram?username=$user"),true);
$order_pk = $contents['info']['id'];
$followers = $contents['info']['followers']; 
$img = $contents['info']['image'];
$amm = $amountorder + $amountorder;
$confirmorder = setOrder($user,$amountorder,$token,$order_pk,$followers,$img);
if(json_decode($confirmorder,true)['message'] == 'Order created successfully'){
	bot('editMessageText',[
	'chat_id'=>$chat_id2,
    'message_id'=>$message_id2,
'text'=>"
تم الرشق بنجاح
عدد الرشق : $amountorder
الشخص الذي تم الرشق اليه : $order_pk
عدد النقاط الذي تم خصمها : $amm
عدد المتابعين عند البدء : $followers
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"الرجوع",'callback_data'=>'back']],
]
])
]);
}else{
bot('answerCallbackQuery', [
    'callback_query_id' => $update->callback_query->id,
    'text' =>"يوجد مشكلة اثناء الرشق
 
    $confirmorder",
    'show_alert' => true,
  ]);
}
}
if($data == 'Support'){
bot('editMessageText',[
	'chat_id'=>$chat_id2,
    'message_id'=>$message_id2,
    'text'=>"
يمكنك دعمي عن طريق الرشق
او عن طريق تحويل النقاط
يمكنك رشق : @el7q.q
و يمكنك تحويل نقاط الى : wjwoxm
وبهذا يمكنك دعمي وشكراً
",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"الرجوع",'callback_data'=>'back']],
]
])
]);
}
}
    };
         $bot = new EzTG(array('throw_telegram_errors'=>true,'token' => $token, 'callback' => $callback));
  }
    catch(Exception $e){
 echo $e->getMessage().PHP_EOL;
 sleep(1);
}
