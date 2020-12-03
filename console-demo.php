<?php

require 'vendor/autoload.php';

use Afzafri\JNTExpressTrackingApi;

if (isset($argv[1])) {
	print_r(JNTExpressTrackingApi::crawl($argv[1]));
} else {
	echo "Usage: " . $argv[0] . " <Tracking code>\n";
}