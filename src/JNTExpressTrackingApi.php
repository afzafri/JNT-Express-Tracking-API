<?php 

namespace Afzafri;

class JNTExpressTrackingApi
{
    public static function crawl($trackingNo, $include_info = false)
    {
	    $url = "https://www.jtexpress.my/tracking/";

		# use cURL instead of file_get_contents(), this is because on some server, file_get_contents() cannot be used
		# cURL also have more options and customizable
		$ch = curl_init(); # initialize curl object
		curl_setopt($ch, CURLOPT_URL, $url . $trackingNo); # set url
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # receive server response
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); # tell cURL to accept an SSL certificate on the host server
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); # tell cURL to graciously accept an SSL certificate on the target server
	    curl_setopt($ch, CURLOPT_TIMEOUT, 5); //timeout in seconds
		$result = curl_exec($ch); # execute curl, fetch webpage content
		$httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); # receive http response status
		$errormsg = (curl_error($ch)) ? curl_error($ch) : "No error"; # catch error message
		curl_close($ch);  # close curl

		$trackres = array();
		$trackres['http_code'] = $httpstatus; # set http response code into the array
	    $trackres['error_msg'] = $errormsg; # set error message into array

	    # use DOMDocument to parse HTML
		$dom = new \DOMDocument();
		libxml_use_internal_errors(true);
		$dom->loadHTML($result);
		libxml_clear_errors();
	    
	    // xpath
	    $xpath = new \DOMXPath($dom);

	    // ----- Get tracking result box -----
	    $trackDetails = $xpath->query("//*[contains(@class, 'container text-start p-1 pt-3')]");

	    if($trackDetails->length > 0) # check if there is records found or not
		{
			$trackres['status'] = 1;
	        $trackres['message'] = "Record Found"; # return record found if number of row > 0

	        foreach ($trackDetails as $index => $detail) 
	        {
	            $tmp_dom = new \DOMDocument(); 
	            $tmp_dom->appendChild($tmp_dom->importNode($detail,true));

	            // xpath
	            $tmp_xpath = new \DOMXPath($tmp_dom);

	            // ----- Get Date -----
	            $trackDate = $xpath->query("//*[contains(@class, 'text-sm-end fs-5 pt-3')]");

	            $date = ($trackDate->length > 0) ? self::formatDate(self::cleanDetail($trackDate[$index]->nodeValue)) : "";
	            $date = ($date) ? $date->format('d/m/Y') : "";

	           // ---- Get Tracking Details----
			   $location = "";
			   $city = "";
			   $process = "";
			   $remark = "";
			   $time = "";

			   $trackDetailsSection = $tmp_xpath->query("//*[contains(@class, 'row')]");

			   if($trackDetailsSection->length > 0){
				   foreach ($trackDetailsSection as $index => $section) {
						$section_dom = new \DOMDocument(); 
						$section_dom->appendChild($section_dom->importNode($section,true));
						$section_xpath = new \DOMXPath($section_dom);

						$getLocation = $section_xpath->query("//*[contains(@class, 'fw-light')]");
						$getTime = $section_xpath->query("//*[contains(@class, 'fw-b mt-3')]");
						$getProcess = $section_xpath->query("//*[contains(@style, 'color:#e60000;')]");
						$getRemark = $section_xpath->query("//*[contains(@class, 'col-7 mt-3')]");

						$location = self::cleanDetail($getLocation[0]->nodeValue);
						$process = self::cleanDetail($getProcess[0]->nodeValue);
						$time = self::cleanDetail($getTime[0]->nodeValue);
						$city = "";

						$filter = [
							"(", 
							")", 
							"Proof of Delivery", 
							self::cleanDetail($getLocation[0]->nodeValue), 
							self::cleanDetail($getProcess[0]->nodeValue)
						];

						$remark = self::cleanDetail(str_replace($filter, "", self::cleanDetail($getRemark[0]->nodeValue)));
					   
						 // Append Data into JSON
						$trackres['data'][] = array(
							"date" => $date,
							"time" => $time,
							"location" => $location,
							"city" => $city,
							"process" => $process,
							"remark" => $remark,
						);
				   }
			   }
	        }
	    } 
	    else 
	    {
	    	$trackres['status'] = 0;
	        $trackres['message'] = "No Record Found"; # return record not found if number of row < 0
	        # since no record found, no need to parse the html furthermore
	    }

		if ($include_info) {
		    $trackres['info']['creator'] = "Afif Zafri (afzafri)";
		    $trackres['info']['project_page'] = "https://github.com/afzafri/JNT-Express-Tracking-API";
		    $trackres['info']['date_updated'] =  "22/09/2020";
		}

		return $trackres;
    }

	static function cleanDetail($str, $explode = false) {
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

	static function formatDate($date, $format = 'd/m/Y') {
	    $time = date('d/m/Y', strtotime($date));
		$datetime = new \DateTime();
		$newDate = $datetime->createFromFormat($format, $time);

	    return ($newDate);
	}

	static function cleanHtml($html) {
	    $patern = '#<div([\w\W]*?)div>#';
	    preg_match_all($patern, html_entity_decode($html), $parsed);

	    $rows = explode("<br>", $parsed[0][0]);

	    $cleaned = array();
	    foreach($rows as $row) {
	        $cleaned[] = strip_tags($row);
	    }
	    
	    return $cleaned;
	}
}
