<?php
  $filecontent = file_get_contents("test.xml");
  $url = 'http://pob-ws.heroku.com/api/hotel_avail_notif';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $filecontent);

  $response = curl_exec($ch);

  curl_close($ch);

  echo $response;


?>