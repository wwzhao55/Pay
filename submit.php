<?php
$config = require 'Api/config.php';
require 'Api/api.php';
require 'Api/cache.php';
require 'Api/function.php';

cache::setCacheDir('./cache');
$api = new api();
$db = $api->get_db();

$time = time();
$level = 0;
$name = isset($_POST['name'])?$_POST['name']:'';
if($name == ''){
	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'姓名不能为空',
 		));
 	exit;
}
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
 		'message'=>'手机号码已使用！'
 		));
 	exit;
}
*/
$code = isset($_POST['code'])?$_POST['code']:'';
if($code==''){
	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'姓名不能为空',
 		));
 	exit;
}
if(!cache::has($mobile)){
	echo json_encode(array(
		'status'=>'fail',
		'message'=>'未获取验证码'
		));
	exit;
}
$code_cache = cache::get($mobile);
if($code_cache['time']-1440 > time()){
	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'验证码已过期，请重新获取',
 		));
 	exit;
}
if($code_cache['code'] != $code){
	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'验证码错误！',
 		));
 	exit;
}

$json_access_token_info = httpGet('wxapi.dgdev.cn/getAccessToken.php');
$array_access_token_info = json_decode($json_access_token_info);
$access_token = $array_access_token_info->access_token;


$ID_card_front_serveID = isset($_POST['ID_card_front_serveID'])?$_POST['ID_card_front_serveID']:'';
$ID_card_back_serveID = isset($_POST['ID_card_back_serveID'])?$_POST['ID_card_back_serveID']:'';
if(!$ID_card_front_serveID || !$ID_card_back_serveID){
	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'证件图片serveID不能为空！',
 		));
 	exit;
}
$front_url =  "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$ID_card_front_serveID}";
$back_url =  "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$ID_card_back_serveID}";

$front_img_info = downloadWeixinFile($front_url);

$back_img_info = downloadWeixinFile($back_url);

if(!checkDownload($front_img_info) || !checkDownload($back_img_info)){
	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'证件图片上传失败！',
 		'front_img_info'=>$front_img_info,
 		'back_img_info'=>$back_img_info,
 		));
 	exit;
}

$front_img_name = time().'_front.jpg';
$back_img_name = time().'_back.jpg';
$license_img_name = time().'_license.jpg';

$result1 = saveWeixinFile('uploads',$front_img_name,$front_img_info['body']);
if(!$result1){
	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'证件正面图片上传失败！',
 		));
 	exit;
}
$result2 = saveWeixinFile('uploads',$back_img_name,$back_img_info['body']);
if(!$result2){
	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'证件背面图片上传失败！',
 		));
 	exit;
}
if (isset($_POST['license_serveID'])) {
	$license_serveID = $_POST['license_serveID'];
	if(!$ID_card_front_serveID){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'证件图片serveID不能为空！',
			));
		exit;
	}
	$license_url =  "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$license_serveID}";
	$license_img_info = downloadWeixinFile($license_url);

	if(!checkDownload($license_img_info)){
		echo json_encode(array(
	 		'status'=>'fail',
	 		'message'=>'证件图片上传失败！',
	 		'front_img_info'=>$front_img_info,
	 		'back_img_info'=>$back_img_info,
	 		));
	 	exit;
	}

	$result3 = saveWeixinFile('uploads',$license_img_name,$license_img_info['body']);
	if(!$result3){
		echo json_encode(array(
	 		'status'=>'fail',
	 		'message'=>'证件图片上传失败！',
	 		));
	 	exit;
	}
}
$result = $db->insert('pay',[
	'name'=>$name,
	'mobile'=>$mobile,
	'ID_card_front'=>'uploads/'.$front_img_name,
	'ID_card_back'=>'uploads/'.$back_img_name,
	'license'=>'uploads/'.$license_img_name,
	'level'=>$level,
	'created_at'=>$time,
	]);
if($result){
	cache::delete($mobile);
	echo json_encode(array(
		'status'=>'success',
		'id'=>$result,
		'message'=>'添加成功'
		));
}else{
	echo json_encode(array(
		'status'=>'fail',
		'message'=>'添加失败'
		));
}



