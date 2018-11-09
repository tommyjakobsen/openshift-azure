<?php


echo "<!DOCTYPE html>
<html>
 <head>
   <title>Azure AI from openShift</title>

<style>
#rcorners1 {
    border-radius: 25px;
    background: #73AD21;
    padding: 20px; 
    width: 600px;
    height: 150px;    
}

#rcorners2 {
    border-radius: 25px;
    border: 2px solid #73AD21;
    padding: 20px; 
    width: 200px;
    height: 150px;    
}
</style>
 </head>
<body>";


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




$jsondata='{
  "documents": [
    {
      "id": "1",
      "text": "Hello world"
    },
    {
      "id": "2",
      "text": "Bonjour tout le monde"
    },
    {
      "id": "3",
      "text": "La carretera estaba atascada. Había mucho tráfico el día de ayer."
    },
    {
      "id": "4",
      "text": ":) :( :D"
    }
  ]
}';


echo "<b>Endpoint: </b>${textAnalyticsEndpoint}/languages<br>";
echo "<b>AppName: </b>$textAnalyticsName<br>";
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
	echo "<br><br>";
	$output=curl_exec($ch);
	curl_close($ch);
	return  $output;
}

$url = "${textAnalyticsEndpoint}/languages";
$output=http_request($url, $textAnalyticsKey, $jsondata);
$url = "${textAnalyticsEndpoint}/sentiment";
$sentiments=json_decode(http_request($url, $textAnalyticsKey, $jsondata));

$postdata=json_decode($jsondata);
$result=json_decode($output);

echo "<h1>Azure Analytics - Language</h1>";
foreach($postdata->documents as $key=>$val)
	{

	echo "<p id='rcorners1'>".$val->text."<br>";
	$id=$val->id;
	echo "<br><br><br><b>Language: </b>".$result->documents[$key]->detectedLanguages[0]->name."<br>";
	echo "<b>ISO:</b> ".$result->documents[$key]->detectedLanguages[0]->iso6391Name."<br>";
	$score=$result->documents[$key]->detectedLanguages[0]->score;
	echo "<b>Certainty: </b>".($score*100)."%</p>";

	}



echo "</body></html>";
