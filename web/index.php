<?php 
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://": "http://";
if (preg_match("/apps\/Cloudflare/",$_SERVER['SCRIPT_URI']))
{
$url = $protocol . $_SERVER['HTTP_HOST'] . "/Cloudflare/index.php";
header($url);
$config_site = '<tbody><tr><td style="padding-right: 7px; width: 47px;" id="app_icon"><img src="/apps/Cloudflare/Cloudflare.png" border="0" style="height: 40px;"></td><td><div class="h1_content header_2"><span id="app_show_name">Cloudflare DDNS and Tunnels</span></div></td></tr><tr><td class="tdfield_padding" colspan="3"><span id="app_description">使用My Cloud更新Cloudflare的DNS设置，以实现DDNS。连接Cloudflare Tunnels，实现内网穿透。</span></td></tr><tr><td colspan="3"><div class="hr_0_content"><div class="hr_1"></div></div><table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="tdfield"><span class="_text">点击按钮打开配置页面</span>:</td><td width="20px"></td><td class="tdfield_padding"><a onclick="location=\'/Cloudflare/index.php\';return false" href="#"><button type="button"><span class="_text">配置</span></button></a></td></tr></tbody></table></td></tr></tbody>';
die($config_site);
}
if ($_COOKIE['login_username'] != 'admin' or count($_COOKIE) < 4)
{
    print_r('<div align="center"><p>' . $_COOKIE['login_username']);
    //print_r($_SERVER['SCRIPT_URI']);
    //header("Location: " . $url);
    //print_r($_COOKIE);
    die('登录状态已失效！</p><p><a href="/">重新登录</a></p></div>');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="PRAGMA" content="no-cache">
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta charset="utf-8">
<title>Cloudflare DDNS and Tunnels</title>
</head>
<body>
<div style="width:480px;margin: auto;margin-top:50px;">
<div class="h1_content header_2">
<span class="_text"><h1>Cloudflare DDNS and Tunnels</h1></span>
</div>

<?php 
include 'INI.class.php'; 
$ini = new INI('./config/cfconfig.ini');
$run_satus = NULL;
if($_SERVER["REQUEST_METHOD"] == 'POST'){
    if($_POST["token"] != '')
    {
        $ini->data['cloudflare']['token'] = $_POST["token"];
    }
    $ini->data['cloudflare']['zone_identifier'] = $_POST["zone_identifier"];
    $ini->data['cloudflare']['identifier'] = $_POST["identifier"];
    $ini->data['cloudflare']['name'] = $_POST["name"];
    $ini->write();
    $curl_run = curl_init('http://localhost/Cloudflare/run.php');
    curl_setopt($curl_run, CURLOPT_RETURNTRANSFER, 1);
    $run_status = curl_exec($curl_run);
    curl_close($curl_run);
}
function geturl($url, $my_head_array = array()){
    $headerArray =array("Content-Type: application/json;","Accept: application/json");
    if(is_array($my_head_array) && count($my_head_array)){
        $headerArray = $my_head_array;
    };
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
$curl_url = "https://api.cloudflare.com/client/v4/zones/" . $ini->data['cloudflare']['zone_identifier'] . "/dns_records/" . $ini->data['cloudflare']['identifier'];
$curl_head = array(
    "Content-Type: application/json",
    "Authorization: Bearer " . $ini->data['cloudflare']['token']
);
$response_json_str = geturl($curl_url, $curl_head);
$res_arr = json_decode($response_json_str, TRUE);
?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<p>API令牌: <input type="password" style="width:350px;float:right;" name="token"><br></p>
<p>获取网址：<a href="https://dash.cloudflare.com/profile/api-tokens">https://dash.cloudflare.com/profile/api-tokens</a><br><br></p>
<p>zone_identifier: <input type="text" style="width:350px;float:right;" name="zone_identifier" value="<?php echo $ini->data['cloudflare']['zone_identifier'] ?>"><br></p>
<p>获取方式：在<a href="https://dash.cloudflare.com/">仪表盘</a>中打开网站概述，右侧下方"区域 ID"。<br><br></p>
<p>identifier: <input type="text" style="width:350px;float:right;" name="identifier" value="<?php echo $ini->data['cloudflare']['identifier'] ?>"><br></p>
<p>获取方式：提交API令牌和zone_identifier后<a href="getid.php">点此获取</a>。<br><br></p>
<p>DDNS域名: <input type="text" style="width:350px;float:right;" name="name" value="<?php echo $ini->data['cloudflare']['name'] ?>"><br></p>
<p>当前设备公网IP:
<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://ipapi.co/ip/");
curl_exec($ch);
curl_close($ch);
?>
</p>
<p>当前域名解析IP:
<?php
if(!is_array($res_arr) || !count($res_arr)){
    print_r('获取失败！'.$response_json_str);
}
elseif(!key_exists('success', $res_arr) || !$res_arr['success']){
    print_r('获取失败！'.$response_json_str);
}
elseif(!key_exists('result', $res_arr)){
    print_r('获取失败！'.$response_json_str);
}
else
{
    echo $res_arr['result']['content'];
}
?>
<br><br></p>
<input type="submit" value="提交">
</form>
<?php

if ($run_status != NULL)
{
    $status_arr = json_decode($run_status, TRUE);
    if($status_arr['success'])
    {
        echo "<p>更新成功！</p>";
    }
    else
    {
        echo "<p>更新失败！</p>";
    }
    echo "<p>详细信息：</p>";
    print_r($run_status);
}

?>
</div>
</body>
</html>


