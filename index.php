<?php


include './head.php';


//ADDING EXPERIMENTAL
echo "<a href='./experimental.php'>[ Experimental ]</a><br>";

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
      "text": "Hi, I just called to say my network is down yet again! This is not good enough. I\'m paying a lot to get the connection working each month..."
    },
    {
      "id": "2",
      "text": "Hi, my network is down. Do you know when this will be up and working again? It is no stress, since I\'m not in hurry. I hope you can fix it within 5 days, then I will be very happy."
    },
    {
      "id": "3",
      "text": "La carretera estaba atascada. Había mucho tráfico el día de ayer."
    },
    {
      "id": "4",
      "text": "Hei, Jeg ser at jeg har 60Gbit linje og det går meget raskt. Er det mulig å oppgradere til 70Gbi Det hadde vært kult om jeg hadde fått det før neste uke, da jeg skal spille et fett spill!"
    }
  ]
}';


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

$url = "${textAnalyticsEndpoint}/languages";
$output=http_request($url, $textAnalyticsKey, $jsondata);
$url = "${textAnalyticsEndpoint}/sentiment";
$sentiments=json_decode(http_request($url, $textAnalyticsKey, $jsondata));


$postdata=json_decode($jsondata);
$result=json_decode($output);

echo "<h1>Azure Text-Analytics - Language/Sentiment</h1>";
foreach($postdata->documents as $key=>$val)
	{

	echo "<p id='rcorners1'>".$val->text."<br>";
	$id=$val->id;
	$sentscore=round((100*$sentiments->documents[$key]->score),0);
	echo "<br><br><b>Language: </b>".$result->documents[$key]->detectedLanguages[0]->name."<br>";
	echo "<b>ISO:</b> ".$result->documents[$key]->detectedLanguages[0]->iso6391Name."<br>";
	$score=$result->documents[$key]->detectedLanguages[0]->score;
	echo "<b>Certainty: </b>".($score*100)."%";
	echo " &nbsp;&nbsp;&nbsp;&nbsp; <b>negative <progress id='p' max='100' value='$sentscore'><span>0</span></progress> positive</b><br>";
	echo "</p>";

	}

echo "<b>Endpoint Language: </b>${textAnalyticsEndpoint}/languages<br>";
echo "<b>Endpoint Sentiment: </b>${textAnalyticsEndpoint}/sentiment<br>";
echo "<b>AppName: </b>$textAnalyticsName<br>";

echo "</body>";
