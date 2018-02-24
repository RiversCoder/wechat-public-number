<?php

	header("Content-Type: text/html; charset=UTF-8");
	
	class  Wechat   
	{      
	    public $APPID="wx46ed101d014f2018";      
	    public $APPSECRET="4107c079d12cfaaa57b64069408f353e";  


	    public function __construct()
	    {
	    	$this->createmenu();
	    }

	    //获取access_token  
	    public function index()  
	    {         
	        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->APPID."&secret=".$this->APPSECRET;        
	        $date=$this->postcurl($url);  
	        $access_token=$date['access_token'];  
	        return $access_token;         
	    }  


	    public function getJson($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);
			return json_decode($output, true);
		}


	    //请求接口方法  
		public function postcurl($url,$data = null)
		{         
			$ch = curl_init();  
			curl_setopt($ch, CURLOPT_URL, $url);  
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);   
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
			if (!empty($data)){  
			    curl_setopt($ch, CURLOPT_POST, TRUE);  
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
			}  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
			$output = curl_exec($ch);  
			curl_close($ch);  
			return  $output=json_decode($output,true);            
		}  

	    //拼接参数，带着access_token请求创建菜单的接口  
	    public function createmenu()
	    {  
	          
	      $data = '{"button":[{"type":"click","name":"今日歌曲","key":"V1001_TODAY_MUSIC"},{"name":"菜单","sub_button":[{"type":"view","name":"搜索","url":"http://www.soso.com/"},{"type":"miniprogram","name":"wxa","url":"http://mp.weixin.qq.com","appid":"wx286b93c14bbf93aa","pagepath":"pages/lunar/index"},{"type":"click","name":"赞一下我们","key":"V1001_GOOD"}]}]}'; 

	      	//echo $data;

			 $access_token=$this->index();  
		     $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;    
		     $result = $this->postcurl($url,$data);  
		   print_r ($result);  
		}             
	}   

	 new Wechat();

	 //7_CIONyuPYBSAjV6GdJPhUSoYI70tsXw2xn9q3JqsxVdwqhrmnlX4v31FyPTS1aQfxR7MSz5cT9H_62ghMR9GnGPgB-D260Yve9-vxntk4vpx5ivhrb14OGqkHWSwLQQjAAAHMM
?>