<?php



/*   
*/
RegisterPlugin("Tuhaokuai", "ActivePlugin_Tuhaokuai");
define('TUHAOKUAI_PATH', dirname(__FILE__));
define('TUHAOKUAI_INCPATH', TUHAOKUAI_PATH . '/inc/');

global $zbp;


/**
 * 对字符串执行指定次数替换
 * @param  Mixed $search   查找目标值
 * @param  Mixed $replace  替换值
 * @param  Mixed $subject  执行替换的字符串／数组
 * @param  Int   $limit    允许替换的次数，默认为-1，不限次数
 * @return Mixed
 */
function Tuhaokuai_str_replace_limit($search, $replace, $subject, $limit=-1){
    if(is_array($search)){
        foreach($search as $k=>$v){
            $search[$k] = '`'. preg_quote($search[$k], '`'). '`';
        }
    }else{
        $search = '`'. preg_quote($search, '`'). '`';
    }
    return preg_replace($search, $replace, $subject, $limit);
}


function Tuhaokuai_is_https()
{
    if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
    {
        return TRUE;
    }
    elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    {
        return TRUE;
    }
    elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
    {
        return TRUE;
    }

    return FALSE;
}
  
function ActivePlugin_Tuhaokuai()
{
	Add_Filter_Plugin('Filter_Plugin_Admin_CommentMng_SubMenu', 'Tuhaokuai_Admin_CommentMng_SubMenu');
	Add_Filter_Plugin('Filter_Plugin_Index_End', 'Tuhaokuai_Core');
	Add_Filter_Plugin('Filter_Plugin_ViewIndex_Begin', 'Tuhaokuai_Core_Start');

	
}

function Tuhaokuai_Core_Start(){

	ob_start();


}

    
function Tuhaokuai_image($content,$tag = 'src'){ 
        $preg = "/<\s*img\s+[^>]*?$tag\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i";
        preg_match_all($preg,$content,$out);
        return $out[2];  
}

function Tuhaokuai_Core()
{
	global $zbp;
	//本地生成图片
	$is_local = true;

 	$re = $_SERVER['REQUEST_URI'];
 	$pre = "Tuhaokuai";
	$name = $pre."_".md5($re);
	 
	$g = $zbp->Config($name)->GetData()['value'];
 	

	$data = ob_get_contents();
	ob_end_clean();
	$stop = false;	
	if($g){

		if(count(Tuhaokuai_image($data)) == count($g)){
			$stop = true;
		}
		 
	 
		
		foreach($g as $v){
			$key = md5(json_encode($v));
			if($exists[$key]){
				$exists[$key]++;
				$newimg[] = ['v'=>$v['src'],'i'=> $exists[$key] ];	
			}else{
				$exists[$key] = 1;
				$newimg[] = ['v'=>$v['src'],'i'=> $exists[$key] ];	
			}
			
			
		}
		
		$flag = true;
	 
		foreach($newimg as $key => $vo){
			  
			$v = $vo['v'];
			$i = $vo['i'];
			$new_url  =  $g[$key]['new_url'];

			$replaced_file = $g[$key]['local'];

//			echo $replaced_file.'<br>'.$new_url."<hr>";


			if(file_exists($replaced_file)){
				$data = Tuhaokuai_str_replace_limit($v,$new_url,$data,$i);	
			}else{
				$flag = false;
			}

			//echo $v."<br>".$new_url.'<br>'.$i."<hr>";
			 
		}


		if($flag === false){
			 
			
			$js =  "<script>
	 
  $(function(){
  	var re = '".$_SERVER['REQUEST_URI']."';
  		$.post('/zb_users/plugin/Tuhaokuai/ajaxCurl.php',{re:re},function(d){

	  	});
  });

  </script>";
		}


    }






	echo $data;
	echo $js;
	if($stop == true){
		exit;
	}

	$js = "<script>
	 

  $(function(){
  	setTimeout(function(){
  		var data = new Object;
  		$('img').each(function(e){
	  		var arr = new Object;
	  		var 
	  		 nWidth = $(this).width(),
			 nHeight = $(this).height();
			 src = $(this).attr('src');

			 arr.src = src;
			 arr.w = nWidth;
			 arr.h = nHeight;
			 arr.re = '".$re."';

			 eval('data.img'+ e +'=arr');
			 

	  	});


	  	$.post('/zb_users/plugin/Tuhaokuai/ajax.php',data,function(d){

	  	});

  	},300);
  	
  	
  });
  
 
	</script>";
	echo $js;

	


}


function Tuhaokuai_Admin_CommentMng_SubMenu()
{
	global $zbp;
	echo '<a href="'. $zbp->host .'zb_users/plugin/Totoro/main.php"><span class="m-right">Totoro设置</span></a>';
}

 
