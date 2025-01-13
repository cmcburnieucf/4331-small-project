<?php

	$inData = getRequestInfo();
	$userId = $inData["userId"];
	
	$searchResults = "";
	$searchCount = 0;

	$conn = new mysqli("localhost", "root", "&&C0P##4331##Pr0ject&&s", "COP4331"); //change this if needed
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$stmt = $conn->prepare("SELECT * FROM Contacts WHERE (FirstName LIKE ? OR LastName LIKE ? OR Phone LIKE ? OR Email LIKE ?) AND UserID=?");
		$searchColVal = $inData["searchColVal"] . "%";
		$stmt->bind_param("ssssi", $searchColVal, $searchColVal, $searchColVal, $searchColVal, $userId);
		$stmt->execute();
		
		$result = $stmt->get_result();
		
		while($row = $result->fetch_assoc())
		{
			if( $searchCount > 0 )
			{
				$searchResults .= ",";
			}
			$searchCount++;
			$searchResults .= '{"FirstName":"' . $row["FirstName"] . '", "LastName":"'. $row["LastName"] .'", "Phone":"'. $row["Phone"] .'", "Email":"'. $row["Email"] .'"}';
		}
		
		if( $searchCount == 0 )
		{
			returnWithError( "No Records Found" );
		}
		else
		{
			returnWithInfo( $searchResults );
		}
		
		$stmt->close();
		$conn->close();
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>
