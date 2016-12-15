<?php
  sendRequest($method = 'GET', $url, $fungsi, $id_device = 1, $args = []) {
    $serverUrl = $url . '?fungsi=' . $fungsi . '&id_device=' . $id_device . '&jml_arg=' . count($args);
    foreach ($args as $key => $value) {
      $serverUrl .= '&arg' . $key '=' . $value;
    }

    $curl = curl_init($serverUrl);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
    if ($method === 'POST') {
      curl_setopt($curl, CURLOPT_POST, true);
    }

    $response = curl_exec($curl);
    return json_decode($response);
  }
?>
