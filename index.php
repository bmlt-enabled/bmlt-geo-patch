<?php
//CONFIGURATION
$table_prefix = "";
$google_maps_api_key = "";
$root_server = "";

$meetings_response = get($root_server . "/client_interface/json/?switcher=GetSearchResults");
$meetings = json_decode($meetings_response);
foreach ($meetings as $meeting) {
    $address = urlencode($meeting->location_street . " "
              . $meeting->location_municipality
              . ", " . $meeting->location_province
              . " " . $meeting->location_postal_code_1);
    $map_details_response = get("https://maps.googleapis.com/maps/api/geocode/json?key=" . $google_maps_api_key
                                . "&address="
                                . urlencode($address));
    $map_details = json_decode($map_details_response);
    if (count($map_details->results) > 0) {
        $geometry      = $map_details->results[0]->geometry->location;
        $new_latitude  = $geometry->lat;
        $new_longitude = $geometry->lng;

        print("UPDATE " . $table_prefix . "_comdef_meetings_main set latitude = '"
              . $new_latitude . "', longitude = '"
              . $new_longitude . "' WHERE id_bigint = "
              . $meeting->id_bigint) . ";\n";
    } else {
        print("-- Could not get coordinates for id: " . $address . " for meeting id: " . $meeting->id_bigint . "\n");
    }
}

function get($url) {
    //error_log($url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0) +yap' );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    $errorno = curl_errno($ch);
    curl_close($ch);
    if ($errorno > 0) {
        throw new Exception(curl_strerror($errorno));
    }

    return $data;
}
