<?php
$CHECKLIST = array(
	'ipaddress' => '127.0.0.1',
	'Origin' => 'https://www.motorolahis.jp',
	'User-Agent' => 'm0t0r0laHIS'
);
$GMAIL = array(
	'host' => 'smtp.gmail.com',
	'port' => 587,
	'auth' => true,
	'charset' => 'utf-8',
	'encoding' => 'base64',
	'secure' => 'tls',
	'username' => 'mothis49@gmail.com', // Gmailのアカウント名
	'password' => 'ogkpbdccxqmpvcit', // Gmailのパスワード(二段階認証)
	'from' => 'res2011@freescale.com', // Fromのメールアドレス(健保代表)
	'fromname' => 'モトローラ健康保険組合'
);

function write_log($log) { // $logの一文字めは成功=1、失敗=0
	$fp = fopen('log/maillog.txt', 'a');
	fwrite($fp, date('c').' '.mb_substr($log, 1).PHP_EOL);
	fclose($fp);
	header("Location: http://www.mediacube.co.jp/services/mail/result.php?result={$log[0]}");       
	exit();
}
?>
