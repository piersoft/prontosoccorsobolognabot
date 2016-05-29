<?php
include("settings_t.php");

$lat=44.6332;
$lon=11.4146;

$urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20&key=".GDRIVEKEY."&gid=0";
$inizio=0;
$homepage ="";

$csv = array_map('str_getcsv',file($urlgd));
//	$csv=str_replace(array("\r", "\n"),"",$csv);
$count = 0;
foreach($csv as $data=>$csv1){
  $count = $count+1;
}

$alert="";
  $optionf=array([]);
  $c=0;
for ($i=$inizio;$i<$count;$i++){

$long10=floatval($csv[$i][4]);
$lat10=floatval($csv[$i][3]);
$theta = floatval($lon)-floatval($long10);
$dist =floatval( sin(deg2rad($lat)) * sin(deg2rad($lat10)) +  cos(deg2rad($lat)) * cos(deg2rad($lat10)) * cos(deg2rad($theta)));
$dist = floatval(acos($dist));
$dist = floatval(rad2deg($dist));
$miles = floatval($dist * 60 * 1.1515 * 1.609344);
$data=0.0;

$t=0;
//	$t=floatval(100*1000);
if ($miles >=1){
  $t=floatval(50);
  $data1=number_format($miles, 2, '.', '');
  $data =number_format($miles, 2, '.', '')." Km";
} else {
  $t=floatval(50*1000);
  $data1=number_format(($miles*1000), 0, '.', '');
  $data =number_format(($miles*1000), 0, '.', '')." mt";

}


  $csv[$i][100]= array("distance" => "value");

  $csv[$i][100]= $data1;
  $csv[$i][101]= array("distancemt" => "value");

  $csv[$i][101]= $data;



      if ($data1 < $t)
      {
        $c++;
        $distanza[$i]['distanza'] =$csv[$i][100];
        $distanza[$i]['distanzamt'] =$csv[$i][101];
      //  $distanza[$i]['id'] =$csv[$i][0];
        $distanza[$i]['lat'] =$csv[$i][3];
        $distanza[$i]['lon'] =$csv[$i][4];
        $distanza[$i]['idpr'] =$csv[$i][6];

      }


}
//echo $homepage;

sort($distanza);
//var_dump($csv);
for ($i=$inizio;$i<$c;$i++){
  array_push($optionf,[$distanza[$i]['idpr']]);

//	if ($distanza[$i]['distanzamt'] !== null)
  $alert .=$distanza[$i]['idpr']."\nDista: ".$distanza[$i]['distanzamt']."\n------\n";
}
echo $alert;

?>
