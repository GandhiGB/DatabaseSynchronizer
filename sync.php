<?php

// Database Constants
define('DB_HOST','localhost');
define('DB_USERNAME','root');
define('DB_PASSWORD','');
define('DB_NAME', 'tccac');

//Connecting to the database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

//checking the successful connection
if($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

//making an array to store the response
$response = array();

//if there is a post request move ahead
if($_SERVER['REQUEST_METHOD']=='POST'){


	//getting the name from request
	$id = $_POST['id'];
	$iid = $_POST['iid'];
	$lname = $_POST['lname'];
	$fname = $_POST['fname'];
	$mname = $_POST['mname'];
	$syncstatus = $_POST['syncstatus'];

	if ($syncstatus == 0) {
		//creating a statement to insert to database
		$stmt = $conn->prepare("INSERT INTO tbl_students (id, iid, lname, fname, mname) VALUES (?,?,?,?,?)");

		//binding the parameter to statement
		$stmt->bind_param("sssss", $id, $iid, $lname, $fname, $mname);

		//if data inserts successfully
		if($stmt->execute()){
			//making success response
			$response['error'] = false;
			$response['iid'] = $iid;
			$response['message'] = 'Name saved successfully';
		}
		else{
			//if not making failure response
			$response['error'] = true;
			$response['message'] = 'Please try later';
		}

		//displaying the data in json format
		echo json_encode($response);
	}

	if ($syncstatus == 2) {
		$sql = "UPDATE tbl_students SET iid='".$iid."', lname = '".$lname."', fname = '".$fname."', mname = '".$mname."'  WHERE id='".$id."'";

		if ($conn->query($sql) === TRUE) {
    		//making success response
			$response['error'] = false;
			$response['iid'] = $iid;
			$response['message'] = 'Name updated successfully';
		} 
		else {
    		//if not making failure response
			$response['error'] = true;
			$response['message'] = 'Error updating record';
		}

		//displaying the data in json format
		echo json_encode($response);
	}


	//displaying the data in json format
	echo json_encode($response);
}

else{

	$response['error'] = true;
	$response['message'] = "Invalid request";

	//displaying the data in json format
	echo json_encode($response);

}
