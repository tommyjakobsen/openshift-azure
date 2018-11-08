<?php


echo "<html>
 <head>
   <title>Azure AI from openShift</title>
 </head>
<body>";


if(getenv('aiEndPoint') !== false)
    {
        $aiEndPoint=getenv('aiEndPoint');
       //Debug
        //echo "Found AI endpoint at: $aiEndPoint<hr>";
     }else{
        echo "<font color=red><b>! \$aiEndPoint variable not set...<br>Check bindings in openshift...</font>";
        echo "</body>";
     exit(1);
     }

if(getenv('aiKey') !== false)
    {
        $aiKey=getenv('aiKey');
        //DEBUG
        //echo "Found aiKey at: $aiKey<hr>";
     }else{
        echo "<font color=red><b>! \$aiKey variable not set...<br>Check bindings in openshift...</font>";
        echo "</body>";
     exit(1);
     }


if(getenv('aiName') !== false)
    {
        $aiName=getenv('aiName');
        //DEBUG
        //echo "Found aiName at: $aiName<hr>";
     }else{
        echo "<font color=red><b>! \$aiName variable not set...<br>Check bindings in openshift...</font>";
        echo "</body>";
     exit(1);
     }




<?php
 
//API Url
$url = "$aiEndPoint";
 
//Initiate cURL.
$ch = curl_init($url);
 
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
 
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
 
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Ocp-Apim-Subscription-Key: $aiKey", "Content-Type: application/json")); 
 
//Execute the request
$result = curl_exec($ch);



echo "</body>";
