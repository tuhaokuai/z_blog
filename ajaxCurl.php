<?php

require dirname(__FILE__).'/../../../zb_system/function/c_system_base.php';
require dirname(__FILE__).'/../../../zb_system/function/c_system_admin.php';

$zbp->Load();

if(!$_POST){
	exit;
}
$api_request = "http://test-b.tuhaokuai.com";


$re  = $_POST['re'];
	
		$pre = "Tuhaokuai";
		$name = $pre."_".md5($re);
		$g = $zbp->Config($name)->GetData()['value'];
if(!$g){
	exit;
}	


foreach($g as $v){
	 
	$src = $v['src'];
	$w = $v['w'];
	$h = $v['h'];
	if(!$src || !$w || !$h){
		continue;
	}
	unset($api);

	$cache = $w."x".$h;
	$p = parse_url($src);
	$domain = $p['host'];
	$image = $p['path'];

	$api['domain'] = $domain;
	$api['image'] = $image;
	$api['cache'] = $cache;
	$file = $v['local'];
	
	if(!file_exists($file)){
		$content = file_get_contents_utf8($api_request,$api);
		
		if($content ){
			echo "write file\n";
			$dir = substr($file,0,strrpos($file,'/'));
			if(!is_dir($dir)){
				mkdir($dir,0777,true);
			}
			file_put_contents($file, $content);
		}
	}
	
	 




}



function file_get_contents_utf8($uri,$data) { 
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $uri );
	curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
	curl_setopt ( $ch, CURLOPT_POST, 1 );
	curl_setopt ( $ch, CURLOPT_HEADER, 0 );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	$return = curl_exec ( $ch );
	$code = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
	curl_close ( $ch );
	if ( strpos($code, '200') !==false ){
        return $return;
    }

    return ; 
} 