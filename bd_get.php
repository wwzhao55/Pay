<?php
require 'Api/api.php';
$api = new api();
$db = $api->get_db();

$all = isset($_GET['all'])?$_GET['all']:0;
$total_count = $db->count('bd',['id[>]'=>0]);
if($total_count==0){
	echo json_encode(array(
		'status'=>'fail',
		'message'=>'暂无数据',
		));
}
if($all==1){
	$result = $db->select('bd','*',['id[>]'=>0]);
}else{
	$rand = rand(0,$total_count-3);
	$result = $db->select('bd','*',['LIMIT'=>[$rand,3]]);
}



if($result){
	echo json_encode(array(
		'status'=>'success',
		'message'=>'获取成功',
		'data'=>$result,
		));
}else{
	echo json_encode(array(
		'status'=>'fail',
		'message'=>'获取失败',
		));
}