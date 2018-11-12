<?php


include './head.php';




//ADDING EXPERIMENTAL
echo "<a href='./index.php'>[ Text Analyse ]</a><br>";


if(getenv('textAnalyticsEndpoint') !== false)
    {
        $textAnalyticsEndpoint=getenv('textAnalyticsEndpoint');
       //Debug
        //echo "Found AI endpoint at: $textAnalyticsEndpoint<hr>";
     }else{
        echo "<font color=red><b>! \$textAnalyticsEndpoint variable not set...<br>Add secrets to application...</font>";
        echo "</body>";
     exit(1);
     }

if(getenv('textAnalyticsKey') !== false)
    {
        $textAnalyticsKey=getenv('textAnalyticsKey');
        //DEBUG
        //echo "Found aiKey at: $textAnalyticsKey<hr>";
     }else{
        echo "<font color=red><b>! \$textAnalyticsKey variable not set...<br>Check bindings in openshift...</font>";
        echo "</body>";
     exit(1);
     }


if(getenv('textAnalyticsName') !== false)
    {
        $textAnalyticsName=getenv('textAnalyticsName');
        //DEBUG
        //echo "Found aiName at: $textAnalyticsName<hr>";
     }else{
        echo "<font color=red><b>! \$textAnalyticsName variable not set...<br>Check bindings in openshift...</font>";
        echo "</body>";
     exit(1);
     }


if(!isset($_GET["postdata"]))
{
echo "<br><br><form action='/experimental.php' id='usrform'>
  Text to analyze:
 	<textarea rows='4' cols='50' name='postdata' form='usrform'>
Enter text here...</textarea>
  <input type='submit'>
</form><br><br>";


}else{

$postdata=preg_replace('/"/', '\"', $_GET["postdata"]);

$replacedata=$postdata;

echo "<h2>Text to analyse:<br></h2>$replacedata<hr>";

$replacedata=urldecode($replacedata);
$jsondata="{
  \"documents\": [
    {
      \"id\": \"1\",
      \"text\": \"$postdata\"
    }
  ]
}";


function http_request($url, $textAnalyticsKey,  $jsondata){	
	//Initiate cURL.
	$ch = curl_init($url);
 	
	//Tell cURL that we want to send a POST request.
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 	
	//Attach our encoded JSON string to the POST fields.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
 	
	//Set the content type to application/json
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Ocp-Apim-Subscription-Key: $textAnalyticsKey", "Content-Type: application/json")); 
 	
	//Execute the request
	$output=curl_exec($ch);
	curl_close($ch);
	return  $output;
}

$url = "${textAnalyticsEndpoint}/entities";
$postdata=json_decode(http_request($url, $textAnalyticsKey, $jsondata));


echo "<h2>Azure Text-Analytics - Keywords</h2>";
foreach($postdata->documents[0]->entities as $key=>$val)
	{
	echo "<a href='".$val->wikipediaUrl."'><b>".$val->name."</b></a><br><br>";
	}
}
echo "<b>Endpoint Language: </b>${textAnalyticsEndpoint}/entities<br>";
echo "<b>AppName: </b>$textAnalyticsName<br>";

echo "</body>";
