<?php
require 'Api/api.php';
require 'Api/cache.php';
cache::setCacheDir('./cache');
$api = new api();
$db = $api->get_db();
$message = $api->get_message();

$mobile = isset($_POST['mobile'])?$_POST['mobile']:'';
$mobile_match ='/^(((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(17([0,1,3]|[6-8]))|(18[0-9]))+\d{8})$/';
if(!preg_match($mobile_match,$mobile)) {
 	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'手机格式不正确'
 		));
 	exit;
}
/*
if($db->has('pay',['mobile'=>$mobile])){
    echo json_encode(array(
        'status'=>'fail',
        'message'=>'手机号码已注册！'
        ));
    exit;
}
*/
$code=array();
if(cache::has($mobile)){
    $cache = cache::get($mobile);
    if(($cache['time'] + 120) > time()){
        $gap = $cache['time'] + 120 - time();
        echo json_encode([
            'status' => 'fail',
            'message' => $gap.'秒后才能重新获取验证码',
            'mobile' => $mobile
            ]);
        exit;
    }
    $code['repeat'] = $cache['repeat'] + 1;
    if($cache['repeat'] > 5){
        echo json_encode([
            'status' => 'fail',
            'message' => '24小时内获取验证码的次数不能超过5次',
            'mobile' => $mobile
            ]);
        exit;
    }
}else{
    //获取验证码的次数
    $code['repeat']=1;
}
$code['code'] = substr(str_shuffle("1234567890"),2,6);
$code['time'] = time();

//发送短信验证码
$content = '【成码科技】您好，您的验证码是'.$code['code'].'，如果并非您本人操作，请忽略本条信息。';
$res = $message->sendCode($mobile,$content);
if(strstr($res,'success')){
    $expireTime = time()+1440;
    cache::set($mobile,$code,$expireTime);
    echo json_encode([
        'status' => 'success',
        'message' => '获取成功',
        'mobile' => $mobile,
        ]);
    exit;
}else{
    echo json_encode([
        'status' => 'fail',
        'message' => '服务器忙，请稍后再试',
        'mobile' => $mobile,
        'echo'=>$res
        ]);
    exit;
}
