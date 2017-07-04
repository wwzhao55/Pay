<?php
require 'Api/api.php';
$api = new api();
$db = $api->get_db();

$id = isset($_POST['id'])?$_POST['id']:null;
$name = isset($_POST['name'])?$_POST['name']:null;
if($id==null){
	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'id不能为空',
 		));
 	exit;
}
if($name==null){
	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'name不能为空',
 		));
 	exit;
}
if(!$db->has('pay',['id'=>$id])){
	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'id不存在',
 		));
 	exit;
}
if(!$db->has('bd',['name'=>$name])){
	echo json_encode(array(
 		'status'=>'fail',
 		'message'=>'name不存在',
 		));
 	exit;
}
$result = $db->update('pay',['BD'=>$name],['id'=>$id]);
if($result){
	echo json_encode(array(
		'status'=>'success',
		'message'=>'修改成功'
		));
	exit;
}else{
	echo json_encode(array(
		'status'=>'fail',
		'message'=>'修改失败'
		));
	exit;
}