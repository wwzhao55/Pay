<?php
require 'Api/api.php';
$api = new api();
$db = $api->get_db();
$page_config = $api->get_page_config();
$page_count = $page_config['PAGE_COUNT'];

$date = isset($_POST['date'])?$_POST['date']:0;
$page = isset($_POST['page'])?$_POST['page']:1;
$today = strtotime(date('Y-m-d'));

if(!$date){//今日数据
	$where = ['created_at[>]'=>$today];
}else{//全部数据
	$where = ['id[>]'=>0];	
}
$data_count = $db->count('pay',$where);

if($data_count){
	if($data_count>$page_count){
		$page_num = ceil($data_count/$page_count);
	}else{
		$page_num = 1;
	}

	if($page_num<$page){
		echo json_encode(array(
			'status'=>'fail',
			'message'=>'页码错误',
			));
		exit;
	}
	$where['LIMIT'] = [($page-1)*$page_count,$page_count];
	$data = $db->select('pay','*',$where);
	echo json_encode(array(
		'status'=>'success',
		'message'=>'获取成功',
		'data'=>$data,
		'data_count'=>$data_count,
		'page_num'=>$page_num
		));
	exit;
}else{
	echo json_encode(array(
		'status'=>'fail',
		'message'=>'暂无数据',
		));
	exit;
}
