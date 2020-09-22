<?php

/*  J&T Express Tracking API created by Afif Zafri.
    Tracking details are fetched directly from J&T Express tracking website,
    parse the content, and return JSON formatted string.
    Please note that this is not the official API, this is actually just a "hack",
    or workaround for implementing J&T Express tracking feature in other project.
    Usage: http://site.com/api.php?trackingNo=CODE , where CODE is your tracking number
*/

header("Access-Control-Allow-Origin: *"); # enable CORS

if(isset($_GET['trackingNo']))
{
    $trackingNo = $_GET['trackingNo']; # put your skynet tracking number here

	$url = "https://www.jtexpress.my/track.php";

	# use cURL instead of file_get_contents(), this is because on some server, file_get_contents() cannot be used
	# cURL also have more options and customizable
	$ch = curl_init(); # initialize curl object
	curl_setopt($ch, CURLOPT_URL, $url."?awbs=".$trackingNo); # set url
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # receive server response
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); # tell cURL to accept an SSL certificate on the host server
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); # tell cURL to graciously accept an SSL certificate on the target server
	$result = curl_exec($ch); # execute curl, fetch webpage content
	$httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); # receive http response status
	$errormsg = (curl_error($ch)) ? curl_error($ch) : "No error"; # catch error message
	curl_close($ch);  # close curl

	$trackres = array();
	$trackres['http_code'] = $httpstatus; # set http response code into the array
    $trackres['error_msg'] = $errormsg; # set error message into array

    # use DOMDocument to parse HTML
	$dom = new DOMDocument();
	libxml_use_internal_errors(true);
	$dom->loadHTML($result);
	libxml_clear_errors();
    
    // xpath
    $xpath = new DOMXPath($dom);

    // ----- Get tracking result box -----
    $trackDetails = $xpath->query("//*[contains(@class, 'tracking-result-box-right-inner')]");

    // ----- Get Pickup date for Year ----
    $pickupDateDiv = $xpath->query("//*[contains(@class, 'input-side track-input')]");
    $pickupDate = ($pickupDateDiv->length > 0) ? formatDate(cleanDetail($pickupDateDiv[0]->nodeValue)) : "";
    $pickupYear = ($pickupDate) ? $pickupDate->format('Y') : "";

    foreach ($trackDetails as $detail) 
    {
        $tmp_dom = new DOMDocument(); 
        $tmp_dom->appendChild($tmp_dom->importNode($detail,true));
        // xpath
        $xpath = new DOMXPath($tmp_dom);

        // ----- Get Date and Time -----
        $trackTime = $xpath->query("//*[contains(@class, 'tracking-point-date-time')]");
        $date = ($trackTime->length > 0) ? formatDate(cleanDetail($trackTime[0]->nodeValue." ".$pickupYear), 'd M Y') : "";
        $date = ($date) ? $date->format('d/m/Y') : "";
        $time = ($trackTime->length > 1) ? cleanDetail($trackTime[1]->nodeValue) : "";

        echo "Date: ". $date."\n";
        echo "Time: ". $time."\n\n";
        
        // $tmp_dom->appendChild($tmp_dom->importNode($detail,true));




        // echo $detail->getElementsByTagName('li')."\n\n";
    }
   
    
    // print_r($result);
}

function cleanDetail($str, $explode = false) {
    if($str != null || $str != "") {
        if($explode) {
            $strArr = explode(":", $str);
            $str = (count($strArr) > 1) ? $strArr[1] : ""; 
        } 

        $converted = strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES))); 
        $str = trim($converted, chr(0xC2).chr(0xA0));
        $str = trim(preg_replace('/\s+/', ' ', $str));
    }

    return $str;
}

function formatDate($date, $format = 'd/m/Y') {
    $datetime = new DateTime();
    $newDate = $datetime->createFromFormat($format, $date);
    return $newDate;
}