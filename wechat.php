<?php
	

	class WeChat{

		public $APPID = "wx46ed101d014f2018";      
	    public $APPSECRET = "4107c079d12cfaaa57b64069408f353e"; 
	    private $_msg_template = array(
	    
		    //text
	        'text' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>',
	        //image
	         'image' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>',
	        //music
	        'music' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[music]]></MsgType><Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl><ThumbMediaId><![CDATA[%s]]></ThumbMediaId></Music></xml>',
	        //news
	        'news' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>%s</ArticleCount><Articles>%s</Articles></xml>',
	        //news_item
	        'news_item' => '<item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item>'
        );  

		public function __construct()
		{
			$this->init();
			$this->getWxMessages();
		}

		private function init()
		{
			//1. 
			$timestamp = $_GET['timestamp'];
			$once = $_GET['nonce'];
			$token = 'wechat';
			$signature = $_GET['signature'];
			$array = array($timestamp,$once,$token);
			sort($array);


			//2. 
			$tmpstr = implode('', $array);
			$tmpstr = sha1($tmpstr);

			//3. 
			if($tmpstr == $signature){

				echo $_GET['echostr'];
				//exit;
			}
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
		

		private function _msgMusic($xml)
		{
			$music_url='http://fs.w.kugou.com/201803011802/c5b339fdc14f1d5bd2c5b49f2e51262d/G123/M04/08/03/uw0DAFpobHGAdIU2ADtVt1s3A-8379.mp3';
            $ha_music_url='http://fs.w.kugou.com/201803011802/c5b339fdc14f1d5bd2c5b49f2e51262d/G123/M04/08/03/uw0DAFpobHGAdIU2ADtVt1s3A-8379.mp3';
            $title = '碧雅-红提';
            $desc = '碧雅是一个森林系小仙女';

            $toUser = $xml->FromUserName;
			$fromUser = $xml->ToUserName;
			$createTime = time();
			$MediaId = 'rBAsdc59TPxTyb7iwnjWEE1XdfVbMFDUOpMNgK0cV8F8WawHyME10fIarfC5TD7h';

            $infoText = sprintf($this->_msg_template['music'],$toUser,$fromUser,$createTime,$title, $desc,$music_url,$ha_music_url,$MediaId);
            
            die($infoText);

		}

		public function getWxMessages()
	    {  

		    $xml_str = $GLOBALS['HTTP_RAW_POST_DATA'];
		 	

		    if(!empty($xml_str))
		    {
		    	file_put_contents('./info.xml',  $xml_str);
		    	$sxe_2 =  simplexml_load_string($xml_str,'SimpleXMLElement', LIBXML_NOCDATA);
		    	$this->_msgMusic($sxe_2);
		    }
		}      
	}


	new WeChat();

	
	
?>