<?php
print_r($_FILES);

$input = $_FILES['audio_data']['tmp_name'];
$output = $_FILES['audio_data']['name'].".wav";

file_put_contents("result.txt","Audio File Was Saved ! > /sounds/".$output);

move_uploaded_file($input, "../../sounds/".$output)
?>
