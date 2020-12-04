<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		$_POST = json_decode(file_get_contents('php://input'), true);

		$response = array();
		$response['index'] = $_POST['index'];

		echo json_encode($response);
	}
?>