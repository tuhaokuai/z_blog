<?php

require dirname(__FILE__).'/../../../zb_system/function/c_system_base.php';
require dirname(__FILE__).'/../../../zb_system/function/c_system_admin.php';

$zbp->Load();

$string = '/upload/thumb/';
	 
$is_local = true;

$top = 'http';
if(Tuhaokuai_is_https()){
	$top = "https";
}
 
$public_url = $_SERVER['HTTP_HOST'];
$public_url = $top."://".$public_url;

$cached_name = '/upload/thumb/';
//缓存URL。
$cache_url = $public_url.$cached_name;
$cache_path  = realpath(dirname(__FILE__).'/../../../');
if(!is_dir($cache_path)){
	mkdir($cache_path,0777,true);
}
$cache_path = $cache_path.'/';

$len = strlen($public_url);
	 

foreach($_POST as $v){
	$re = $v['re'];
	if(!$re){
		exit;
	}
	unset($v['re']);
	$old_src  = $src = $v['src'];
	$w = $v['w'];
	$h = $v['h'];
	if(strpos($src,'upload/thumb/')!==false){
		$src1 = substr($src,strpos($src,$string)+strlen($string));
		$src1 = substr($src1,0,strpos($src1,'/'));
		$array = explode('x',$src1);
		$v['w'] = $w = $array[0];
		$v['h'] = $h = $array[1];
		$v['src'] = $old_src  = preg_replace('/upload\/thumb\/\d+x\d+\//', '', $src);
 
 
	}

	$cache_string = $w.'x'.$h;
	$replaced =  substr($old_src,$len);
	$new_url  = $cache_url.$cache_string.$replaced;
	$replaced_file = $cache_path.$cached_name.$cache_string.$replaced;
	$replaced_file = str_replace('//', '/', $replaced_file);
	$v['new_url'] = $new_url;
	$v['local'] = $replaced_file;
	$v['cache_url'] = $cache_url;


	$arr[$re][] = $v;
}




$pre = "Tuhaokuai";
$name = $pre."_".md5($re);
$g = $zbp->Config($name)->GetData()['value'];

if(md5(json_encode($g)) == md5(json_encode($arr[$re]))){
	exit('no changed!');
}
foreach($arr as $k=>$v){
	 
	$zbp->Config($name)->value = $v;
	$zbp->SaveConfig($name);
}


 

exit;