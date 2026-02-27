<?php
$url = "https://raw.githubusercontent.com/namangangsta/react-native-philippines-map/master/src/components/Map.js";
$content = file_get_contents($url);
echo substr($content, 0, 1000);
?>
