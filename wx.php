<?php
	

	class WeChat{

		public $APPID = "wx46ed101d014f2018";      
	    public $APPSECRET = "4107c079d12cfaaa57b64069408f353e";
	    private $_appkey = '73c59be8dff14916a0d9ff5fb90c31b6'; 
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
	        'news_item' => '<item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item>',
	        'geography' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[location]]></MsgType><Location_X>%s</Location_X><Location_Y>%s</Location_Y><Scale>%s</Scale><Label><![CDATA[红提子信息]]></Label><MsgId>1234567890123456</MsgId></xml>',
	        'geography_2' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[event]]></MsgType><Event><![CDATA[LOCATION]]></Event><Latitude>%s</Latitude><Longitude>%s</Longitude><Precision>30.000000</Precision></xml>'
        );  

	    // construct
		public function __construct()
		{
			$this->init();
			$this->keepSituation();
			//$this->getWxMessages();
			//$this->getFiles();
		}

		//get all nums
		private function getFiles()
		{	
			$access_token = $this->index();
			$url='https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$access_token;
			$result = $this->postcurl($url);

			print_r($result);
		}

		//init default function
		private function keepSituation()
		{	
			print_r($_POST);
			print_r($_FILES);

			//判断是否有上传文件
			if(isset($_POST['uploadBtn']) && !empty($_FILES['fileGood']))
			{	
				$filePath = './images/tpicture.jpg';
				move_uploaded_file($_FILES['fileGood']['tmp_name'],$filePath);
				$this->uploadFiles($filePath,$_POST['fileType']);
			}
		}

		//update file(image/voice/video/thumb) and get media_id
		private function uploadFiles($filepath,$filetype)
		{	
			$access_token = $this->index();
			$type = $filetype;
			$file = $filepath;
			if(class_exists('\CURLFile'))
			{
				$data = array('media' => new \CURLFile(realpath($file)) );
			}
			else
			{
				$data = array( 'media' => '@'.realpath($file) );
			}
			
			$url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type='.$type;
			$result = $this->postcurl($url,$data);

			print_r($result);
		}

		// token setting
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
		
		

		// Reply text
		private function _msgText($xml)
		{
			
            $toUser = $xml->FromUserName;
			$fromUser = $xml->ToUserName;
			$createTime = time();
			$content = '您好，欢迎来到mo vi的公众号!';

            $infoText = sprintf($this->_msg_template['text'],$toUser,$fromUser,$createTime,$content);
            
            die($infoText);

		}

		private function _msgAutoText($xml)
		{
			
            $toUser = $xml->FromUserName;
			$fromUser = $xml->ToUserName;
			$content = $xml->Content;
			$createTime = time();
			$url = 'http://www.tuling123.com/openapi/api?key='.$this->_appkey.'&info='.$content.'&userid='.$toUser;
			$response_content = $this->postcurl($url);
			
            $infoText = sprintf($this->_msg_template['text'],$toUser,$fromUser,$createTime,$response_content['text']);
            
            die($infoText);

		}	

		// Reply image
		private function _msgImg($xml)
		{
			
            $toUser = $xml->FromUserName;
			$fromUser = $xml->ToUserName;
			$createTime = time();
			$MediaId = 'rBAsdc59TPxTyb7iwnjWEE1XdfVbMFDUOpMNgK0cV8F8WawHyME10fIarfC5TD7h';

            $infoText = sprintf($this->_msg_template['image'],$toUser,$fromUser,$createTime,$MediaId);
            
            die($infoText);
		}

		// Reply news
		private function _msgNews($xml)
		{
			
            $toUser = $xml->FromUserName;
			$fromUser = $xml->ToUserName;
			$createTime = time();
			$item_str = '';
			$newsList = array(
				array('title'=>'漂亮的小碧雅','desc'=>'碧雅是一个森林系小仙女','picurl'=>'http://pic1.win4000.com/wallpaper/2017-12-19/5a387cdbbdfe0.jpg','url'=>'blog.sina.com.cn/riversfrog'),
				array('title'=>'胖胖的帅阿达','desc'=>'阿达是一个重量级小胖子','picurl'=>'http://pic1.win4000.com/wallpaper/2017-12-19/5a387cfd18684.jpg','url'=>'blog.sina.com.cn/riversfrog'),
				array('title'=>'黑黑的小阿俊','desc'=>'阿达是一个重量级小胖子','picurl'=>'http://pic1.win4000.com/wallpaper/2017-12-19/5a387cc3af0c0.jpg','url'=>'blog.sina.com.cn/riversfrog')
			);

			foreach ($newsList as $item) {
				$item_str .= sprintf($this->_msg_template['news_item'],$item['title'],$item['desc'],$item['picurl'],$item['url']);
			}

            $infoText = sprintf($this->_msg_template['news'],$toUser,$fromUser,$createTime,count($newsList),$item_str);
            
            die($infoText);

		}		

		// Reply music
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

		// Reply geography
		private function _msgGeography($xml)
		{
			$toUser = $xml->FromUserName;
			$fromUser = $xml->ToUserName;
			$createTime = time();
			$px = '114.072333';
			$py = '22.614635';
			//$pscale = '20';

            $infoText = sprintf($this->_msg_template['geography_2'],$toUser,$fromUser,$createTime,$px,$py);
            file_put_contents('2.xml',$infoText);
            die($infoText);
		}


		// Accept message
		public function getWxMessages()
	    {  

		    $xml_str = $GLOBALS['HTTP_RAW_POST_DATA'];
		 	

		    if(!empty($xml_str))
		    {
		    	file_put_contents('./info.xml',  $xml_str);
		    	$sxe_2 =  simplexml_load_string($xml_str,'SimpleXMLElement', LIBXML_NOCDATA);
		    	//$this->_msgMusic($sxe_2);
		    	//$this->_msgImg($sxe_2);
		    	//$this->_msgText($sxe_2);
		    	//$this->_msgNews($sxe_2);
		    	//$this->_msgGeography($sxe_2);
		    	$this->_msgAutoText($sxe_2);
		    }
		}      
	}


	new WeChat();
?>