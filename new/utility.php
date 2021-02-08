<?php
	session_start();
	
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

	//check whether the session is set
	function checkSession(){
		if(!isset($_SESSION['session_id'])){
			if(!isset($_COOKIE['session_id'])){
				header('Location: memberLogin.php');
			}else{
				$_SESSION['session_id'] = $_COOKIE['session_id'];
			}
		}
	}

	function getUserIdFromSessionId(){
		$properties['columns'] = Column::USER_ID;
		$properties['condition'] = "WHERE session_id = '".$_SESSION['session_id']."'";
		$properties['orderBy'] = "";
		$properties['limit'] = "";
		$database = new Database(DB::INFO, DB::USER, DB::PASS);
		$dbTable = new DbTable($database, Table::SESSION_TB); 
		$dbTableQuery = new DbTableQuery($properties);
		$dbTableOperator = new DbTableOperator();
		$row = $dbTableOperator->read($dbTable, $dbTableQuery, new DbPrepareResult());

		if($row == null){
			setcookie('session_id', '', time() - 60);
			header('Location: memberLogin.php');
		}else{
			return $row[0][Column::USER_ID];
		}
	}
?>