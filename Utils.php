<?php
  function sendRequest($url, $fungsi, $id_device = 1, $args = []) {
    $serverUrl = $url . '/?fungsi=' . $fungsi;

    $serverUrl .= '&jml_arg=' . count($args);
    foreach ($args as $key => $value) {
      $serverUrl .= '&arg' . $key . '=' . $value;
    }

    if (is_array($id_device)) {
      $responses = [];

      foreach ($id_device as $id) {
        $_ = $serverUrl . '&id_device=' . $id;
        $curl = curl_init($_);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1000);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        array_push($responses, json_decode($response));
      }

      return $responses;
    } else {
      $serverUrl .= '&id_device=' . $id_device;
      $curl = curl_init($serverUrl);
      curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1000);
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($curl);
      return json_decode($response);
    }
  }

  function pretty_print($content) {
    echo '<p>';
    if (is_object($content)) {
      echo '[<] ';
      echo json_encode($content, JSON_PRETTY_PRINT);
    } else {
      echo '[>] send get request to <a href='.$content.'>'.$content.'</a>';
    }
    echo '</p>';
  }
?>
