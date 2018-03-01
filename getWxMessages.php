<?php
	
	//获取用户向微信公众号发送的信息

	header("Content-Type: text/html; charset=UTF-8");
	
	class  Wechat   
	{      
	    public $APPID="wx46xxxxxf2018";      
	    public $APPSECRET="4107c079xxxxxxx408f353e";  


	    public function __construct()
	    {
	    	$this->getWxMessages();
	    }

	    //get access_token
	    public function index()  
	    {         
	        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->APPID."&secret=".$this->APPSECRET;        
	        $date=$this->postcurl($url);  
	        $access_token=$date['access_token'];  
	        return $access_token;         
	    }  


	    // requst url
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

	    // get user's send messages 
	    public function getWxMessages()
	    {  
			 $access_token=$this->index();  

		     $xml_str = $GLOBALS['HTTP_RAW_POST_DATA'];
		     $sxe_2 =  simplexml_load_string($xml_str);
		     echo '123';
		     echo $xml_str;
		     var_dump($sxe_2);
		}             
	}


	 new Wechat();
?>