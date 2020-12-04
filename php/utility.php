<?php
    /*GET EXAMPLE
    echo httpGet("http://hayageek.com");
    */
    function httpGet($url)
    {
        $ch = curl_init();  

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //  curl_setopt($ch,CURLOPT_HEADER, false); 

        $output=curl_exec($ch);

        curl_close($ch);
        return $output;
    }

    
    /* POST EXAMPLE
    $params = array(
       "name" => "Ravishanker Kusuma",
       "age" => "32",
       "location" => "India"
    );
 
    echo httpPost("http://hayageek.com/examples/php/curl-examples/post.php",$params);
    */
    function httpPost($url, $params)
    {
        $postData = '';
        //create name value pairs seperated by &
        foreach($params as $k => $v) 
        { 
            $postData .= $k . '='.$v.'&'; 
        }
        $postData = rtrim($postData, '&');
     
        $ch = curl_init();  
     
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);   
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
     
        curl_close($ch);

        return $output;
     
    }

    function sendEmail($params){
        $output = httpPost("http://rexthonyy.000webhostapp.com/apps/personal/EmailSender/sendEmail.php", $params);
        $data = json_decode($output, true);

        if($data == null){
            return false;
        }else{
            return $data['status'] == 'success' ? true : false;
        }
    }
?>