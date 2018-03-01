<?php
	
	
	//1. 将timestamp，nonce,token按字典序排序
	$timestamp = $_GET['timestamp'];
	$once = $_GET['nonce'];
	$token = 'wechat';
	$signature = $_GET['signature'];
	$array = array($timestamp,$once,$token);
	sort($array);


	//2. 将排序后三个参数凭借后用sha1加密
	$tmpstr = implode('', $array);
	$tmpstr = sha1($tmpstr);

	//3. 将加密后的字符串与signature进行对比，判断该请求是否来自微信
	if($tmpstr == $signature){

		echo $_GET['echostr'];
		exit;
	}



	class WeChat{

		public $APPID="wx46exxxxxxxf2018";      
	    public $APPSECRET="4107c079d12xxxxxxxx08f353e"; 
	    private $_msg_template = array(
	    
	    //文本回复XML模板
        'text' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>',
        //图片回复XML模板
        'image' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>',
        //音乐模板
        'music' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[music]]></MsgType><Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl><ThumbMediaId><![CDATA[%s]]></ThumbMediaId></Music></xml>',
        // 新闻主体
        'news' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>%s</ArticleCount><Articles>%s</Articles></xml>',
        //某个新闻模板
        'news_item' => '<item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item>',
    );  

		function __construct()
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


		private function _msgText($xml)
		{	
			$toUser = $xml->FromUserName;
			$fromUser = $xml->ToUserName;
			$createTime = time();
			$content = '你好哇,欢迎来到你我的微信殿堂!';

			$infoText = sprintf($this->_msg_template['text'],$toUser,$fromUser,$createTime,$content);

			die($infoText);
		}

		public function getWxMessages()
	    {  
			
		    $xml_str = $GLOBALS['HTTP_RAW_POST_DATA'];
		 
		    if(!empty($xml_str))
		    {
		    	file_put_contents('./info.xml',  $xml_str);
		    	$sxe_2 =  simplexml_load_string($xml_str,'SimpleXMLElement', LIBXML_NOCDATA);
		    	$this->_msgText($sxe_2);
		    }
		}      
	}

	new WeChat();
	
?>