<?php //DeleteContact.php
    
    include_once 'UtilFunctions.php';
    
	$inData = getRequestInfo();

	$firstName = $inData["firstName"];
	$lastName = $inData["lastName"];
	$phone = $inData["phone"];
	$email = $inData["email"];
	$userId = $inData["userId"];
	//$contactId = $inData["contactId"];

	$conn = new mysqli("localhost", "James98", "password", "COP4331"); //change this if needed
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		//$stmt = $conn->prepare("DELETE FROM Contacts WHERE UserId=? AND ID=?");
		//$stmt->bind_param("ii", $userId, $contactId);
		$stmt = $conn->prepare("DELETE FROM Contacts WHERE (FirstName LIKE ? OR LastName LIKE ? OR Phone LIKE ? OR Email LIKE ?) AND UserId=?");
		$stmt->bind_param("ii", $firstName, $lastName, $phone, $email, $userId);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		returnWithError("");
	}
	
?>
