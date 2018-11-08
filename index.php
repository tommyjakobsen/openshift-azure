<?php


echo "<html>
 <head>
   <title>Azure AI from openShift</title>
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
        $aiName=getenv('textAnalyticsName');
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
 
//API Url
$url = "${textAnalyticsEndpoint}/languages";
 
//Initiate cURL.
$ch = curl_init($url);
 
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
 
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
 
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Ocp-Apim-Subscription-Key: $textAnalyticsKey", "Content-Type: application/json")); 
 
//Execute the request
$result = curl_exec($ch);



echo "</body>";
