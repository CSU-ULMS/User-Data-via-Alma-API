<?php
//update users from local database
//db connection info - uncomment to activate script
//include("../../users/dbconn.php");
include("config.php");

//db connection and tests
if(isset($host)){
  $database = "alma_legacy_barcodes";
  $linkID = mysql_connect($host, $dbuser, $pass) or die("Could not connect to host.");
  mysql_select_db($database, $linkID) or die("Could not find database.");

  //base query to retrieve guide data
  $query = "SELECT username,barcode FROM legacy_barcodes ORDER BY username ASC";
  $resultID = mysql_query($query, $linkID) or die("Data not found.");

  $templateParamNames = array('{user_id}');

  $ch = curl_init();
  $ch2 = curl_init();

  for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){

    $row = mysql_fetch_assoc($resultID);
    $userid = $row['username'];
    $userbarcode = $row['barcode'];
    $templateParamValues = array(urlencode($userid));
    $urlbase = 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/users/{user_id}';
    $url = str_replace($templateParamNames, $templateParamValues, $urlbase);
    $queryParams = '?' . urlencode('user_id_type') . '=' . urlencode('all_unique') . '&' . urlencode('view') . '=' . urlencode('full') . '&' . urlencode('apikey') . '=' . urlencode('l7xxb2e40b3c1ba1456792f30e66f413cda3');
    curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $usernode = curl_exec($ch);

    $xml = new SimpleXMLElement($usernode);

    if (!strstr($usernode, "errorsExist")){ 
      $newid = $xml->xpath("//user_identifiers")[0]->addChild("user_identifier");
      $newid->addAttribute("segment_type", "Internal");
      $newid->addChild("id_type","OTHER_ID_1");
      $newid->addChild("value",$userbarcode);
      $newid->addChild("status","ACTIVE");

      $usernodeupdate = $xml->asXML();
      $userdatafragment = substr($usernodeupdate, strpos($usernodeupdate, '?'.'>') + 2);

      if (!strstr($usernode, "errorsExist")){ 
        $puturl = str_replace($templateParamNames, $templateParamValues, $urlbase);
        $queryPutParams = '?' . urlencode('user_id_type') . '=' . urlencode('all_unique') . '&' . urlencode('apikey') . '=' . urlencode('l7xxb2e40b3c1ba1456792f30e66f413cda3');
        curl_setopt($ch2, CURLOPT_URL, $puturl . $queryPutParams);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch2, CURLOPT_HEADER, FALSE);
        curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch2, CURLOPT_POSTFIELDS, $usernodeupdate);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
        $response = curl_exec($ch2);
        file_put_contents("response.xml", $userid . $response, FILE_APPEND);
      } else {
        file_put_contents("errors.xml", $userdatafragment, FILE_APPEND);      
      }
    }
  }
  curl_close($ch);
  curl_close($ch2);
  echo $x;
}
?>