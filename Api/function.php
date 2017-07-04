<?php
function downloadWeixinFile($url)  {  
    $ch = curl_init($url);  
    curl_setopt($ch, CURLOPT_HEADER, 0);      
    curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头  
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    $package = curl_exec($ch);  
    $httpinfo = curl_getinfo($ch);  
    curl_close($ch);  
    $imageAll = array_merge(array('header' => $httpinfo), array('body' => $package));   
    return $imageAll;  
}  

function checkDownload($download){
    $body_json = $download['body'];
    return  is_null(json_decode($body_json));
}
   
function saveWeixinFile($newfolder,$filename, $filecontent)  {     
    createFolder($newfolder);  
    $local_file = fopen($newfolder."/".$filename, 'w');  
    if (false !== $local_file){  
          
        if (false !== fwrite($local_file, $filecontent)) {  
          
            fclose($local_file); 
            return true; 
         
        }else{
            return false;
        }
    }else{
        return false;
    }  
}  
function createFolder($path) {  
    if (!file_exists($path))  {  
        createFolder(dirname($path));  
        mkdir($path, 0777);  
    }  
}  

function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }