<?php
include 'INI.class.php'; 
$ini = new INI('./config/cfconfig.ini');

    function geturl($url, $my_head_array = array()){
        $headerArray =array("Content-Type: application/json;","Accept: application/json");
        if(is_array($my_head_array) && count($my_head_array)){
            $headerArray = $my_head_array;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
 
    $curl_url = "https://api.cloudflare.com/client/v4/zones/" . $ini->data['cloudflare']['zone_identifier'] . "/dns_records";
    $curl_head = array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $ini->data['cloudflare']['token']
    );
        
    
    $response_json_str = geturl($curl_url, $curl_head);
    //print_r($response_json_str);
    $res_arr = json_decode($response_json_str, TRUE);
    if(!is_array($res_arr) || !count($res_arr)){
        die('Sorry, invalid response：<br />'.$response_json_str);
    };
    if(!key_exists('success', $res_arr) || !$res_arr['success']){
        die('Failed to get the list of zones: <br />'.$response_json_str);
    };
    
    if(!key_exists('result', $res_arr)){
        die('Missing key `result`: <br />'.$response_json_str);
    };
    
    
    $list_ori = $res_arr['result'];
    $final_list = array();
    foreach ($list_ori as $k => $oneZone){
        $final_list[] = array(
            'domain' => strtolower($oneZone['name']),
            'id' => $oneZone['id']
        );
    }
    
    
    $count_zones = count($final_list);
    $i = 1;
    echo "<h3>{$x_email}</h3>";
    echo '{';
    echo '<br />';
    foreach ($final_list as $oneZone) {
        echo '    "'.$oneZone['domain'].'":"'.$oneZone['id'].'"';
        if($i != $count_zones){
            echo ',';
        }
        echo '<br />';
        $i++;
    }
    echo '}';
    die();
?>