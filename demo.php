<!DOCTYPE html>
<html>
<head>
	<title>J&T API Demo</title>
</head>
<body>

	<?php

	$trackingNo = ""; # your tracking number
	$url = "http://localhost/jntAPI/api.php?trackingNo=".$trackingNo; # the full URL to the API
	$getdata = file_get_contents($url); # use files_get_contents() to fetch the data, but you can also use cURL, or javascript/jquery json
	$parsed = json_decode($getdata,true); # decode the json into array. set true to return array instead of object

	$httpcode = $parsed["http_code"];
	$message = $parsed["message"];

	echo $message . "<br>";

	if($message == "Record Found" && $httpcode == 200)
	{
		?>

		<table border=1>
			<tr>
				<th>Date</th>
				<th>Time</th>
				<th>Process</th>
				<th>Location</th>
				<th>City</th>
				<th>Remarks</th>
			</tr>
			<?php
				
				# iterate through the array
				for($i=0;$i<count($parsed['data']);$i++) 
				{
					# access each items in the JSON
					echo "
						<tr>
							<td>".$parsed['data'][$i]['date']."</td>
							<td>".$parsed['data'][$i]['time']."</td>
							<td>".$parsed['data'][$i]['process']."</td>
							<td>".$parsed['data'][$i]['location']."</td>
							<td>".$parsed['data'][$i]['city']."</td>
							<td>".$parsed['data'][$i]['remark']."</td>
						</tr>
						";
				}
	}
	?>

	</table>

</body>
</html>