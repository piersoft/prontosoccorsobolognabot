<?php
include("settings_t.php");
$id=$_GET['id'];

$url="http://www.salute.bologna.it/index.php?p=dettagliProntoSoccorso&PRSO_ID=".$id;

$html = file_get_contents($url);

$html=str_replace("<![CDATA[","",$html);
$html=str_replace("]]>","",$html);
$html=str_replace("</br>","",$html);
$html=str_replace("</br>","",$html);
$html=str_replace("&nbsp;","",$html);
$html=str_replace(";"," ",$html);
$html=str_replace(","," ",$html);


$doc = new DOMDocument;
$doc->loadHTML($html);

$xpa    = new DOMXPath($doc);
//var_dump($doc);
$divsl   = $xpa->query('//tr[2]/td[@class="content"]/div[@class="PSCOL"]/div[@class="rilevato"]');
$divs0   = $xpa->query('//table/tbody/tr[2]/td[@class="codeDescription"]');
$divs1   = $xpa->query('//table/tbody/tr[4]/td[@class="codeDescription"]');
$divs2   = $xpa->query('//table/tbody/tr[6]/td[@class="codeDescription"]');
$divs3   = $xpa->query('//table/tbody/tr[8]/td[@class="codeDescription"]');
$dival=[];
$diva0=[];
$diva=[];
$diva17=[];
$diva18=[];
$diva19=[];
$diva1=[];
$diva2=[];
$diva3=[];
$diva4=[];
$diva5=[];
$diva6=[];
$diva7=[];
$diva8=[];
$diva9=[];
$diva10=[];
$diva11=[];
$diva12=[];
$diva13=[];
$diva14=[];
$diva15=[];
$diva16=[];
$count=0;
foreach($divs0 as $div0) {
$count++;
      array_push($diva0,$div0->nodeValue);
}
//  echo "Count: ".$count."</br>";

foreach($divsl as $divl) {

    array_push($dival,$divl->nodeValue);
}


foreach($divs1 as $div1) {

      array_push($diva1,$div1->nodeValue);
}
foreach($divs2 as $div2) {

      array_push($diva2,$div2->nodeValue);
}
foreach($divs3 as $div3) {

      array_push($diva3,$div3->nodeValue);
}
foreach($divs4 as $div4) {

      array_push($diva4,$div4->nodeValue);
}


//$count=3;

$option=[];
for ($i=0;$i<$count;$i++){

$alert.=$dival[$i]."</br>";
$alert.= "</br>📕 ";
$alert.= trim($diva3[$i])."</br>📒 ".trim($diva2[$i])."</br>📗 ".trim($diva1[$i])."</br>📁 ".trim($diva0[$i])."</br></br>";
$alert.="</br></br>";

}

echo $alert;

 ?>
