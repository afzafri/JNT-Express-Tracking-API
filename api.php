<?php

require 'vendor/autoload.php';

use Afzafri\JNTExpressTrackingApi;

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

    $trackres = JNTExpressTrackingApi::crawl($trackingNo, true);

    # output/display the JSON formatted string
    echo json_encode($trackres);
}

