<?php
header('Content-type: text/xml');
$userid = $_GET["userid"];
$templateParamNames = array('{user_id}');
$templateParamValues = array(urlencode($userid));
$userrecord = new DOMDocument();
$userrecord->loadXML("<userdata/>");
$f = $userrecord->createDocumentFragment();

$ch = curl_init();
$url = 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/users/{user_id}';
$url = str_replace($templateParamNames, $templateParamValues, $url);
$queryParams = '?' . urlencode('user_id_type') . '=' . urlencode('all_unique') . '&' . urlencode('view') . '=' . urlencode('full') . '&' . urlencode('apikey') . '=' . urlencode('l7xxb2e40b3c1ba1456792f30e66f413cda3');
curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$alluserdata = curl_exec($ch);
curl_close($ch);

$alluserdatafragment = substr($alluserdata, strpos($alluserdata, '?'.'>') + 2);
$f->appendXML($alluserdatafragment);

$ch = curl_init();
$url = 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/users/{user_id}/loans';
$url = str_replace($templateParamNames, $templateParamValues, $url);
$queryParams = '?' . urlencode('apikey') . '=' . urlencode('l7xxb2e40b3c1ba1456792f30e66f413cda3');
curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$loansresponse = curl_exec($ch);
curl_close($ch);

$loansresponsefragment = substr($loansresponse, strpos($loansresponse, '?'.'>') + 2);
$f->appendXML($loansresponsefragment);

$ch = curl_init();
$url = 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/users/{user_id}/fees';
$url = str_replace($templateParamNames, $templateParamValues, $url);
$queryParams = '?' . urlencode('user_id_type') . '=' . urlencode('all_unique') . '&' . urlencode('status') . '=' . urlencode('ACTIVE') . '&' . urlencode('apikey') . '=' . urlencode('l7xxb2e40b3c1ba1456792f30e66f413cda3');
curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$feesdata = curl_exec($ch);
curl_close($ch);

$feesdatafragment = substr($feesdata, strpos($feesdata, '?'.'>') + 2);
$f->appendXML($feesdatafragment);

$ch = curl_init();
$url = 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/users/{user_id}/requests';
$url = str_replace($templateParamNames, $templateParamValues, $url);
$queryParams = '?' . urlencode('apikey') . '=' . urlencode('l7xxb2e40b3c1ba1456792f30e66f413cda3');
curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$requestsdata = curl_exec($ch);
curl_close($ch);

$requestsdatafragment = substr($requestsdata, strpos($requestsdata, '?'.'>') + 2);
$f->appendXML($requestsdatafragment);

$userrecord->documentElement->appendChild($f);
echo $userrecord->saveXML(); 
?>