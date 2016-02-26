<?php
include("config.php");
header('Content-type: text/xml');
$userid = $_GET["userid"];
$templateParamNames = array('{user_id}');
$templateParamValues = array(urlencode($userid));

$ch = curl_init();
$url = 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/users/{user_id}';
$url = str_replace($templateParamNames, $templateParamValues, $url);
$queryParams = '?' . urlencode('user_id_type') . '=' . urlencode('all_unique') . '&' . urlencode('view') . '=' . urlencode('full') . '&' . urlencode('apikey') . '=' . urlencode($apikey);
curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$usernode = curl_exec($ch);
curl_close($ch);

echo $usernode;
?>