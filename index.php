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




echo "</body>";
