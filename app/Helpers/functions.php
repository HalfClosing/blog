<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ApartmentModel;
use App\Http\Api\Ding;
function testCommon(){
	var_dump("ok"); 
}


//判断客户端访问设备
if(!function_exists('is_mobile_request')){
	function is_mobile_request()  {  
	$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
		$mobile_browser = '0';  

	if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
		$mobile_browser++;  

	if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
		$mobile_browser++;  

	if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
		$mobile_browser++;  

	if(isset($_SERVER['HTTP_PROFILE']))  
		$mobile_browser++;  

	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
	$mobile_agents = array(  
	    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
	    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
	    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
	    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
	    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
	    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
	    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
	    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
	    'wapr','webc','winw','winw','xda','xda-'
	    );  
	if(in_array($mobile_ua, $mobile_agents))  
		$mobile_browser++;  

	if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  
		$mobile_browser++;  

	// Pre-final check to reset everything if the user is on Windows  
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  
		$mobile_browser=0;  

	// But WP7 is also Windows, with a slightly different characteristic  
	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  
		$mobile_browser++;  

	if($mobile_browser>0)  
		return true;  
	else
		return false;
	}
}

if(!function_exists('write_error')){
	function write_error($data){
		$errorData['error'] = $data;
		DB::table('error_log')->insert($errorData);
	}
}

if(!function_exists('ownApiResponse')){
	function ownApiResponse($status,$message,$data = array())
	{
		$result['status'] = $status;
		$result['message'] = $message;
		$result['data'] = $data;
		return $result;
	}
}

if(!function_exists('getUpdateSql')){
	function getUpdateSql($data,$table_name)
	{
		$table_keys = array_keys($data[0]);
		
		foreach ($data as $key => $value) {
			$sqlString = "(";
			foreach ($value as $k => $v) {
				$value[$k] = "'".$v."'";
			}
			$sqlString .= implode(",", $value);
			$Data[] = $sqlString .= ")";
		}
		$contentSql = implode(",",$Data);
		$keysString = implode(",", $table_keys);
		$table_name = $table_name;
		foreach ($table_keys as $key => $value) {
			$tmp[] = $value."=values(".$value.")"; 
		}

		$endSql = implode(",", $tmp);

		$sql = "insert INTO $table_name (".$keysString.") values".$contentSql." on duplicate key update ".$endSql;
		return $sql;
	}
}


