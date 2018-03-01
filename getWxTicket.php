<?php
	
	//生成关注微信号的二维码

	header("Content-Type: text/html; charset=UTF-8");
	
	class  Wechat   
	{      
	    public $APPID="wx46exxxxxx2018";      
	    public $APPSECRET="4107c079dxxxxxxxxxxxxx08f353e";  


	    public function __construct()
	    {
	    	$this->getWxTicket();
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


	    // get wx ticket
	    public function getWxTicket()
	    {  
			$access_token=$this->index();  
			$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='. $access_token;
			$data = '{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}';

			$result = $this->postcurl($url,$data);
			var_dump($result);

			//输出验证码图片
			$ticketUrl = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($result['ticket']);
			echo '<img src='.$ticketUrl.' />';
		}             
	}


	 new Wechat();
?>