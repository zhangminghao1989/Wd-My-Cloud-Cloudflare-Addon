<?php 
include 'INI.class.php'; 
$ini = new INI('./config/cfconfig.ini');

$chip = curl_init();
curl_setopt($chip, CURLOPT_URL, "https://ipapi.co/ip/");
curl_setopt($chip, CURLOPT_RETURNTRANSFER, 1);
$ip = curl_exec($chip);
curl_close($chip);

$curl_url = "https://api.cloudflare.com/client/v4/zones/" . $ini->data['cloudflare']['zone_identifier'] . "/dns_records/" . $ini->data['cloudflare']['identifier'];
$curl_head = array(
    "Content-Type: application/json",
    "Authorization: Bearer " . $ini->data['cloudflare']['token']
);
$curl_data = array("content"=>$ip,"name"=>$ini->data['cloudflare']['name'],"type"=>"A");
$data_string = json_encode($curl_data);
function puturl($url, $my_head_array = array(), $data){
    $headerArray =array("Content-Type: application/json;","Accept: application/json");
    if(is_array($my_head_array) && count($my_head_array)){
        $headerArray = $my_head_array;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

    $output = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) {
        //echo "cURL Error #:" . $err;
        return $err;
    } else {
        //echo $output;
        return $output;
    }
}

$status = puturl($curl_url,$curl_head,$data_string);
print_r($status);
?>


