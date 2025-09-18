<?php
// Executed every minute by crond.
define('API_URL', 'https://www.motorolahis.jp/member/cgi-bin/api-mail.php');
define('LOG_FILE', '/usr/local/motomail/log/log.txt');
require '/usr/local/motomail/phpmailer/PHPMailerAutoload.php';
require '/usr/local/motomail/vendor/autoload.php';
$mail = get_mail(API_URL);
if ($mail['to'] == 'NULL') {
  echo 'NULL'.PHP_EOL;
  exit(1);
} else {
  $gmail = array('host' => 'smtp.gmail.com', 'port' => 587, 'auth' => true, 'charset' => 'utf-8', 'encoding' => 'base64', 'secure' => 'tls', 'username' => 'mothis49@gmail.com', 'password' => 'ogkpbdccxqmpvcit', 'from' => 'res2011@freescale.com', 'fromname' => 'モトローラ健康保険組合');
  $result = send_mail($gmail, $mail);
  if ($result['ret']) {
    echo 'SEND'.PHP_EOL;
  } else {
    echo 'ERROR'.PHP_EOL;    
  }
  $contents = date('Y-m-d H:i:s').' '.$result['log'].PHP_EOL;
  file_put_contents(LOG_FILE, $contents.file_get_contents(LOG_FILE));
  exit(0);
}

function get_mail($url)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $res = curl_exec($ch);
  curl_close($ch);
  return json_decode($res, true);
}

function send_mail($gmail, $mail)
{
  $mailer = new PHPMailer(true);
  try {
    $mailer->isSMTP();
    $mailer->Host       = $gmail['host'];
    $mailer->SMTPAuth   = $gmail['auth'];
    $mailer->Username   = $gmail['username'];
    $mailer->Password   = $gmail['password'];
    $mailer->SMTPSecure = $gmail['secure'];
    $mailer->CharSet    = $gmail['charset'];
    $mailer->Encoding   = $gmail['encoding'];
    $mailer->Port       = $gmail['port'];
    $mailer->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
    $mailer->setFrom($gmail['from'], $gmail['fromname']);
    $mailer->Subject = $mail['subject'];
    $mailer->Body = $mail['body'];
    $mailer->AddAddress($mail['to']);
    if ($mail['cc'] != '') {
      $mailer->AddCC($mail['cc'], '');
    }
    $mailer->send();
    $ret = true;
    $log = "sent to {$mail['to']} {$mail['subject']}";
  } catch (Exception $e) {
    $ret = false;
    $log = "error: {$mailer->ErrorInfo} -> {$mail['to']}";
  }
  return ['ret' => $ret, 'log' => $log];
}
?>
