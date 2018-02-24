<?php

  header("Content-Type: text/html; charset=UTF-8");
  
  class  Wechat   
  {      
      public $APPID="wx46exxxxx2018";      
      public $APPSECRET="4107c0xxxxxxxxxxxxx8f353e";  


      public function __construct()
      {
        $this->createmenu();
      }

      //»ñÈ¡access_token  
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


      //ÇëÇó½Ó¿Ú·½·¨  
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

      //Æ´½Ó²ÎÊý£¬´ø×Åaccess_tokenÇëÇó´´½¨²Ëµ¥µÄ½Ó¿Ú  
      public function createmenu()
      {  
            
        $data = '{ 
       "button":[ 
        {  
         "type":"click", 
         "name":"今日music", 
         "key":"V1001_TODAY_MUSIC"
        }, 
        { 
         "type":"click", 
         "name":"歌手简介", 
         "key":"V1001_TODAY_SINGER"
        }, 
        { 
         "name":"菜单", 
         "sub_button":[ 
         {  
          "type":"view", 
          "name":"搜索", 
          "url":"http://www.soso.com/"
         }, 
         { 
          "type":"view", 
          "name":"视频", 
          "url":"http://v.qq.com/"
         }, 
         { 
          "type":"click", 
          "name":"赞一下我们", 
          "key":"V1001_GOOD"
         }] 
        }] 
      }'; 

           //echo $data;

       $access_token=$this->index();  
       echo $access_token;
         $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;    
         $result = $this->postcurl($url,$data);  
       print_r ($result);  
    }             
  }   

   new Wechat();

   //7_CIONyuPYBSAjV6GdJPhUSoYI70tsXw2xn9q3JqsxVdwqhrmnlX4v31FyPTS1aQfxR7MSz5cT9H_62ghMR9GnGPgB-D260Yve9-vxntk4vpx5ivhrb14OGqkHWSwLQQjAAAHMM
?>