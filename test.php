<?php
require 'Api/api.php';
$api = new api();
$db = $api->get_db();
$result = $db->insert('pay',[
	'name'=>"xuxuxu",
	'mobile'=>"xuxuxu",
	'ID_card_front'=>"xuxuxu",
	'ID_card_back'=>"xuxuxu",
	'level'=>1,
	'created_at'=>11111,
	]);
if($result){
	echo json_encode(array(
		'id'=>$result,
		'status'=>'success',
		'message'=>'添加成功'
		));
}else{
	echo json_encode(array(
		'status'=>'fail',
		'message'=>'添加失败'
		));
}