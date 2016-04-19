<?php
$rate_request = array(
	"GGG" => array(								// Limit 25 Items
		"Service" => "ONLINE",					//
		"FirstClassMailType" => "LETTER", 		//
		"ZipOrigination" => "12345", 			//
		"ZipDestination" => "90210", 			//
		"Pounds" => "0", 						//
		"Ounces" => "6", 						//
		"Container" => "",						//
		"Size" => "REGULAR", 					//
		"Machinable" => "false"					//
	)
);
$zip_request = array(
	"RES_1" => array(					//	Limit 5 Items
		"FirmName" => "XYZ Corp.",		//
		"Address1" => "",				//	
		"Address2" => "6406 Ivy Lane", 	//
		"City" => "Greenbelt", 			//
		"State" => "MD"					//
	)
);
$city_state_request = array(
	"ZIP_1" => array(					//	Limit 5 Items
		"Zip5" => "20770"				//
	)
);
$track_request = array(
	"9274897642094701142116"			// Limit 1 Item
);

// 
// 
// Still Need to add code for Address Verification
// and Label Service
// 
// 


function Rate($array){
	$requestString = build_XML_LEV2R($array, "Package", "RateV4Request");
	
	$request = "http://production.shippingapis.com/ShippingAPI.dll?API=RateV4&XML=" . rawurlencode($requestString);
                   
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $request);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($ch);
	
	// Potentially faster and less dense code for request
	//$response = file_get_contents($request);
	
	curl_close($ch);
	
	return simplexml_load_string($response);
}
function ZipcodeLookup($array){
	$requestString = build_XML_LEV2($array, "Address", "ZipCodeLookupRequest");
	$request = "http://production.shippingapis.com/ShippingAPI.dll?API=ZipCodeLookup&XML=" . rawurlencode($requestString);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $request);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($ch);
	curl_close($ch);
	
	return simplexml_load_string($response);
}
function CityStateLookup($array){
	$requestString = build_XML_LEV2($array, "ZipCode", "CityStateLookupRequest");
	$request = "http://production.shippingapis.com/ShippingAPI.dll?API=CityStateLookup&XML=" . rawurlencode($requestString);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $request);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($ch);
	curl_close($ch);
	
	return simplexml_load_string($response);
}
function Track($array){
	$requestString = build_XML_LEV1($array, "TrackID", "TrackRequest");
	$request = "http://production.shippingapis.com/ShippingAPI.dll?API=TrackV2&XML=" . rawurlencode($requestString);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $request);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($ch);
	curl_close($ch);
	
	return simplexml_load_string($response);
}
function TrackField($array){
	$requestString = build_XML_LEV1($array, "TrackID", "TrackFieldRequest");
	$request = "http://production.shippingapis.com/ShippingAPI.dll?API=TrackV2&XML=" . rawurlencode($requestString);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $request);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($ch);
	curl_close($ch);
	
	return simplexml_load_string($response);
}
function build_XML_LEV1($array, $delimeter, $request_type){
	$xml = "<$request_type USERID=\"{HERE}\" PASSWORD=\"{AND HERE}\">";
	foreach ($array as $id) {
		$xml  = $xml . "<$delimeter ID=\"$id\"></$delimeter>";
	}
	$xml  = $xml . "</$request_type>";
	
	return $xml;
}
function build_XML_LEV2($array, $delimeter, $request_type){
	$xml = "<$request_type USERID=\"{HERE}\" PASSWORD=\"{AND HERE}\">";
	foreach ($array as $id=>$attribute) {
		$xml  = $xml . "<$delimeter ID=\"$id\">";
		foreach ($attribute as $key => $value) {
			$xml  = $xml . "<$key>$value</$key>";
		}
		$xml  = $xml . "</$delimeter>";
	}
	$xml  = $xml . "</$request_type>";
	
	return $xml;
}
function build_XML_LEV2R($array, $delimeter, $request_type){
	$xml = "<$request_type USERID=\"{HERE}\" PASSWORD=\"{AND HERE}\"><Revision>2</Revision>";
	foreach ($array as $id=>$attribute) {
		$xml  = $xml . "<$delimeter ID=\"$id\">";
		foreach ($attribute as $key => $value) {
			$xml  = $xml . "<$key>$value</$key>";
		}
		$xml  = $xml . "</$delimeter>";
	}
	$xml  = $xml . "</$request_type>";
	
	return $xml;
}
//======================================================
//			Store Rates Associated with Service
//======================================================
function store_rates($rate){
	$table = array();
	foreach ($rate as $package => $array) {
		foreach ($array as $attribute => $array) {
			$name = "";
			foreach ($array as $key => $value) {
				if ($key === "MailService") {
					$name = implode(explode("&trade;",implode(explode("&reg;",strip_tags(html_entity_decode($value))))));
					$table["$name"] = array();
				}
				if ($key === "Rate" && $value != "0.00") {
					$table["$name"]["Rate"] = $value;
				}
				if ($key === "CommercialRate") {
					$table["$name"]["CommercialRate"] = $value;
				}
			}
		}
	}
	return $table;
}

print_r(Track($track_request));
//print_r(TrackField($track_request));
//print_r(ZipcodeLookup($zip_request));
//print_r(CityStateLookup($city_state_request));
//print_r(Rate($rate_request));
//print_r($rate_request);
//print_r(store_rates(Rate($rate_request)));
//======================================================
//			Display Easily Readable Rates
//======================================================
//foreach ($rate as $package => $array) {
//	foreach ($array as $attribute => $array) {
//		foreach ($array as $key => $value) {
//			if ($key === "MailService") {
//				$value = implode(explode("&trade;",implode(explode("&reg;",strip_tags(html_entity_decode($value))))));
//				echo "\n\n", $value;
//			}
//			if ($key === "Rate" && $value != "0.00") {
//				echo "\n\t\t\t  ",$key,"\t|\t",$value;
//			}
//			if ($key === "CommercialRate") {
//				echo "\n\t",$key,"\t|\t",$value;
//			}
//		}
//	}
//}

//======================================================
//			First Class vs Media Mail Rates
//======================================================
//function service_pricing($pound, $ounce) {
//	$table = array();
//	foreach ($pound as $y) {
//		foreach ($ounce as $x) {
//			if (($x + $y) == 0)  {
//				continue;
//			}
//			$rateALL = Rate(
//				array(
//					"AAA" => array(
//						"Service" => "ALL",
//						"FirstClassMailType" => "LETTER",
//						"ZipOrigination" => "12345",
//						"ZipDestination" => "90210",
//						"Pounds" => "$y",
//						"Ounces" => "$x",
//						"Container" => "",
//						"Size" => "REGULAR",
//						"Machinable" => "false"
//					)
//				)
//			);
//			$rateFIRST = Rate(
//				array(
//					"AAA" => array(
//						"Service" => "FIRST CLASS",
//						"FirstClassMailType" => "LETTER",
//						"ZipOrigination" => "12345",
//						"ZipDestination" => "90210",
//						"Pounds" => "$y",
//						"Ounces" => "$x",
//						"Container" => "",
//						"Size" => "REGULAR",
//						"Machinable" => "false"
//					)
//				)
//			);
//			$entry = array_merge(store_rates($rateALL),store_rates($rateFIRST));
//			$index = ($y*16) + ($x);
//			$table[$index] = $entry;
//		}
//	}
//	return $table;
//}
//function store_first_vs_media() {
//	$pound = array(0);
//	$ounce = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13);
//	$table = service_pricing($pound, $ounce);
//
//	$header = "Ounces,First-Class Mail,Media Mail\n";
//	foreach ($table as $entry => $array) {
//		$header .= $entry.",";
//		foreach ($array as $service => $array) {
//			if ($service == "First-Class Mail Parcel") {
//				$header .= $array['Rate'].",";
//				continue;
//			}
//			if ($service == "Media Mail") {
//				$header .= $array['Rate'];
//				continue;
//			}
//		}
//		$header .= "\n";
//	}
//	$myFile = "pricing.csv";
//	$fh = fopen($myFile, 'w') or die("can't open file");
//	fwrite($fh, $header);
//	fclose($fh);
//}


?>