<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_URL, "https://ssstik.io/abc?url=dl");
curl_setopt($ch, CURLOPT_POSTFIELDS, "id=https://www.tiktok.com/@user1125972829390/video/7214535874816527643?is_from_webapp=1&sender_device=pc&locale=en&tt=MSKD02AS");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$hasil = curl_exec($ch);

var_dump($hasil);