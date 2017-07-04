<?php
require 'Api/api.php';
$api = new api();
$db = $api->get_db();
$id = isset($_POST['id'])?$_POST['id']:'';
if($id==''){
	echo json_encode(array(
		'status'=>'fail',
		'message'=>'id不能为空',
		));
	exit;
}
$has_id = $db->has('pay',['id'=>$id]);
if(!$has_id){
	echo json_encode(array(
		'status'=>'fail',
		'message'=>'id不存在',
		));
	exit;
}
$level = $db->get('pay','level',['id'=>$id]);
if($level==0){
	$result = $db->update('pay',['level'=>1],['id'=>$id]);
}else{
	$result = $db->update('pay',['level'=>0],['id'=>$id]);
}
if($result){
	echo json_encode(array(
		'status'=>'success',
		'message'=>'level更新成功',
		));
	exit;
}else{
	echo json_encode(array(
		'status'=>'fail',
		'message'=>'level更新失败',
		));
	exit;
}
