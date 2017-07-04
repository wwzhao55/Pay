<?php
require 'Api/api.php';

$date = isset($_GET['date'])?$_GET['date']:1;
$today = strtotime(date('Y-m-d'));
if(!$date){//今日数据
	$where = ['created_at[>]'=>$today];
	$title = '注册信息表-'.date('Y-m-d');
}else{//全部数据
	$where = ['id[>]'=>0];	
	$title = '注册信息表';
}

$api = new api();
$db = $api->get_db();
$data = $db->select('pay','*',$where);
if(count($data)==0){
	echo '暂无数据导出！！';
	exit;
}
$api->get_excel($title,$data);