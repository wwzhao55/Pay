<?php 
/**
* author:xuxuxu
* 
*/
class send{
    //验证码短信
    public function sendCode($mobile,$content){
        $url = 'http://m.5c.com.cn/api/send/?';
        $username = 'dataguiding';     //用户账号
        $password = 'qwer1234';   //密码
        $apikey = 'b966f93d0035a4ec78b4d27118bc85b7';   //密码
        //$content = ;        //内容
        $data = array
            (
            'username'=>$username,                  //用户账号
            'password'=>$password,              //密码
            'mobile'=>$mobile,                  //号码
            'content'=>$content,                //内容
            'apikey'=>$apikey,                  //apikey
            );
        $result= $this->curlSMS($url,$data);           //POST方式提交
        return $result;
    }

    public function test(){
        return 'message';
    }
    
    private function curlSMS($url,$post_fields=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600); //60秒 
        curl_setopt($ch, CURLOPT_HEADER,1);
        curl_setopt($ch, CURLOPT_REFERER,'http://www.yourdomain.com');
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_fields);
        $data = curl_exec($ch);
        curl_close($ch);
        $res = explode("\r\n\r\n",$data);
        return $res[2]; 
    }
}