	<?php
/**
* Telegram Bot PS Bologna Lic. CC-BY 4.0 art52 CAD, Powered by Francesco "Piersoft" Paolicelli
*/

include("Telegram.php");
include("settings_t.php");

class mainloop{
const MAX_LENGTH = 4096;
function start($telegram,$update)
{

	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");
	$text = $update["message"] ["text"];
	$chat_id = $update["message"] ["chat"]["id"];
	$user_id=$update["message"]["from"]["id"];
	$location=$update["message"]["location"];
	$reply_to_msg=$update["message"]["reply_to_message"];

	$this->shell($telegram,$text,$chat_id,$user_id,$location,$reply_to_msg);
	$db = NULL;

}

//gestisce l'interfaccia utente
 function shell($telegram,$text,$chat_id,$user_id,$location,$reply_to_msg)
{
	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");
if (strpos($text,'/start') === false ){
//	$text =str_replace("/","",$text);
}
if (strpos($text,'@prontosoccorsobolognabot') !== false) $text =str_replace("@prontosoccorsobolognabot ","",$text);
	if ($text == "/start" || $text == "Informazioni") {
		$img = curl_file_create('logo.png','image/png');
		$contentp = array('chat_id' => $chat_id, 'photo' => $img);
		$telegram->sendPhoto($contentp);
		$reply = "Benvenuto. Questo è un servizio automatico per gli accessi in tempo reale dei ".NAME." http://www.salute.bologna.it/index.php?p=elencoProntoSoccorso. \n
		📕 -> Codice rosso: molto critico, pericolo di vita, priorità massima, accesso immediato alle cure\n
		📒 -> Codice giallo: mediamente critico, presenza di rischio evolutivo, possibile pericolo di vita\n
		📗 -> Codice verde: poco critico, assenza di rischi evolutivi, prestazioni differibili\n
		📂 -> Codice bianco: non critico, prestazioni differibili\n
Questo bot è stato realizzato da @piersoft e non è collegato in alcun modo con http://www.salute.bologna.it/ che è titolare dei dati con licenza CC-BY 4.0 secondo il CAD (openbydefault). Il progetto e il codice sorgente sono liberamente riutilizzabili con licenza MIT. Le coordinate dei P.S. sono state ricavate dal DB di openStreetMap con licenza odbl.
\nPer tutti i miei Bot -> http://www.piersoft.it/?p=626";
		$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
		$log=$today. ",new_info,," .$chat_id. "\n";
		file_put_contents(LOG_FILE, $log, FILE_APPEND | LOCK_EX);

		$this->create_keyboard_temp($telegram,$chat_id);
		exit;
	}elseif ($text == "/Più vicini" || $text == "Più vicini") {
		$reply = "Invia la tua posizione tramite la 📎 per avere i PS nel raggio di 50km. Ti consigliamo di controllare anche su http://www.salute.bologna.it/index.php?p=elencoProntoSoccorso nel caso ci siano punti di Pronto Intervento stagionali che potrebbero essere attivi";
		$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
		$this->create_keyboard_temp($telegram,$chat_id);
		exit;
	}elseif ($text == "/Bologna" || $text == "Bologna") {
		$log=$today. ",Bologna,".$text."," .$chat_id. "\n";
		file_put_contents(LOG_FILE, $log, FILE_APPEND | LOCK_EX);

		$this->create_keyboard_bo($telegram,$chat_id);
		exit;
	}elseif ($text == "/Provincia" || $text == "Provincia") {
		$log=$today. ",Provincia,".$text."," .$chat_id. "\n";
		file_put_contents(LOG_FILE, $log, FILE_APPEND | LOCK_EX);

		$this->create_keyboard_pv($telegram,$chat_id);
		exit;
	}

		elseif($location != null)
		{

			$lon=$location["longitude"];
			$lat=$location["latitude"];

			$content = array('chat_id' => $chat_id, 'text' => "Elaborazione, attendere...",'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);

			$urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20WHERE%20D%20IS%20NOT%20NULL%20&key=".GDRIVEKEY."&gid=0";
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
							$distanza[$i]['idpr'] =$csv[$i][5];

			      }


			}
			//echo $homepage;

			sort($distanza);
			for ($i=$inizio;$i<$c;$i++){
				array_push($optionf,[$distanza[$i]['idpr']]);

			//	if ($distanza[$i]['distanzamt'] !== null)
				$alert .=$distanza[$i]['idpr']."\nDista: ".$distanza[$i]['distanzamt']."\n------\n";
			}
			$telegram->buildKeyBoardHide(true);
			$telegram->buildForceReply(true);
			$keyb = $telegram->buildKeyBoard($optionf, $onetime=false);
			$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $alert);
			$telegram->sendMessage($content);

			$reply="Mappa con tutti i PS: http://u.osmfr.org/m/87735/";
			$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $reply);
			$telegram->sendMessage($content);

			$log=$today. ",coordinate,".$text."," .$chat_id. "\n";
			file_put_contents(LOG_FILE, $log, FILE_APPEND | LOCK_EX);


		}
		elseif(strpos($text,'PS') !== false)
		{
			$content = array('chat_id' => $chat_id, 'text' => "Elaborazione, attendere...",'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);
			$gid=GDRIVEGID1;
			$url="http://www.salute.bologna.it/index.php?p=dettagliProntoSoccorso&PRSO_ID=";
			if (strpos($text,'PS Oculistico - Via Palagi') !== false){
					$url .="9007";
			}elseif (strpos($text,'PS Generale - Via Albertoni') !== false){
					$url .="5007";
			}elseif (strpos($text,'PS Ortopedico - Via Albertoni') !== false){
					$url .="7007";
			}elseif (strpos($text,'PS Pediatrico - Via Massarenti') !== false){
					$url .="6007";
			}elseif (strpos($text,'PS Ostetrico-Ginecologico') !== false){
					$url .="8007";
			}
			elseif (strpos($text,'Rizzoli - PS Ortopedico Traumatologico') !== false){
					$url .="4007";
			}elseif (strpos($text,'PS Generale Ospedale Maggiore') !== false){
					$url .="2008";
			}elseif (strpos($text,'PS Ospedale Maggiore - Ostetricia e Ginecologia') !== false){
					$url .="1007";
			}elseif (strpos($text,'PS Pediatrico Ospedale Maggiore - Largo Nigrisoli') !== false){
					$url .="2007";
			}elseif (strpos($text,'PS SAN LAZZARO DI SAVENA') !== false){
					$url .="1009";
			}elseif (strpos($text,'PS PORRETTA TERME') !== false){
					$url .="1049";
			}elseif (strpos($text,'PS BENTIVOGLIO') !== false){
					$url .="1005";
			}elseif (strpos($text,'PS BUDRIO') !== false){
					$url .="1008";
			}elseif (strpos($text,'PS SAN GIOVANNI IN PERSICETO') !== false){
					$url .="1053";
			}elseif (strpos($text,'PS VERGATO') !== false){
					$url .="1059";
			}elseif (strpos($text,'PS BAZZANO') !== false){
					$url .="1004";
			}elseif (strpos($text,'PS LOIANO') !== false){
					$url .="1034";
			}elseif (strpos($text,'PS Pediatria BENTIVOGLIO') !== false){
					$url .="1006";
			}

				$html = file_get_contents($url);

				$html=str_replace("<![CDATA[","",$html);
				$html=str_replace("]]>","",$html);
				$html=str_replace("</br>","",$html);
				$html=str_replace("\n","",$html);
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
				$diva1=[];
				$diva2=[];
				$diva3=[];
				$diva4=[];

				$count=0;
				foreach($divs0 as $div0) {
				$count++;
				      array_push($diva0,$div0->nodeValue);
				}
				//  echo "Count: ".$count."\n";
if ($count ==0){
	$content = array('chat_id' => $chat_id, 'text' => "Nessun dato disponibile",'disable_web_page_preview'=>true);
	$telegram->sendMessage($content);
	$this->create_keyboard_temp($telegram,$chat_id);
	exit;
}
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

				$alert.=$dival[$i]."\n";
				$alert.= "\n📕 ";
				$alert.= trim($diva3[$i])."\n📒 ".trim($diva2[$i])."\n📗 ".trim($diva1[$i])."\n📁 ".trim($diva0[$i])."\n\n";
				$alert.="\n\n";

				}
$chunks = str_split($alert, self::MAX_LENGTH);
foreach($chunks as $chunk) {
	$content = array('chat_id' => $chat_id, 'text' => $chunk,'disable_web_page_preview'=>false);
	$telegram->sendMessage($content);
		}

						$log=$today. ",ricerca,".$text."," .$chat_id. "\n";
						file_put_contents(LOG_FILE, $log, FILE_APPEND | LOCK_EX);

		$this->create_keyboard_temp($telegram,$chat_id);
		exit;
		}

	}

	function create_keyboard_temp($telegram, $chat_id)
	 {
			 $option = array(["Bologna","Provincia"],["Più vicini","Informazioni"]);
			 $keyb = $telegram->buildKeyBoard($option, $onetime=false);
			 $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Fai la tua ricerca.]");
			 $telegram->sendMessage($content);
	 }

	 function create_keyboard_bo($telegram, $chat_id)
	  {
	 		 $option = array(["PS Generale - Via Albertoni","PS Oculistico - Via Palagi"],["PS Ortopedico - Via Albertoni","PS Pediatrico - Via Massarenti"],["PS Ostetrico-Ginecologico - Via Massarenti","PS Ortopedico Traumatologico - Via G.C.Pupilli"],["PS Generale Ospedale Maggiore - Largo Nigrisoli","PS Ortopedico Ospedale Maggiore - Largo Nigrisoli"],["PS Ospedale Maggiore - Ostetricia e Ginecologia - Via dell'Ospedale","PS Pediatrico Ospedale Maggiore - Largo Nigrisoli"],["Più vicini","Informazioni"]);
	 		 $keyb = $telegram->buildKeyBoard($option, $onetime=false);
	 		 $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Fai la tua ricerca.]");
	 		 $telegram->sendMessage($content);
	  }

		function create_keyboard_pv($telegram, $chat_id)
		 {
				 $option = array(["PS SAN LAZZARO DI SAVENA","PS PORRETTA TERME"],["PS BENTIVOGLIO","PS BUDRIO"],["PS SAN GIOVANNI IN PERSICETO","PS VERGATO"],["PS BAZZANO","PS LOIANO"],["PS Pediatria BENTIVOGLIO"],["Più vicini","Informazioni"]);
				 $keyb = $telegram->buildKeyBoard($option, $onetime=false);
				 $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Fai la tua ricerca.]");
				 $telegram->sendMessage($content);
		 }
}



	 ?>
