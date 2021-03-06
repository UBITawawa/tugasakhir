<?php
  // JSON HEADER
  header('Content-type: application/json');
  require('Utils.php');

  // REDIS
  // $client = new TinyRedisClient( 'host:port' );
  // $client->set( 'key', 'value' );
  // $value = $client->get( 'key' );
  require('TinyRedisClient.php');
  $client = new TinyRedisClient('localhost:6379');

  // CONST
  define(SOUND_VOLUME, 'VOLUME');
  define(SOUND_MUTE, 'MUTE');
  define(PRESENTATION_ACTION, 'ACTION');
  define(PRESENTATION_FILE, 'FILE');
  define(MAX_DEVICE, 3);

  // WHEN USING ID_DEVICE, JUST APPEND IT
  // EXAMPLE SOUND_VOLUME.$ID_DEVICE

  // OTHER DEVICE'S IP (kalau tidak jadi upnp)
  define(KELOMPOK_LAMPU, '10.10.100.203');
  define(KELOMPOK_SOUND, '10.10.100.205');
  define(KELOMPOK_JENDELA, '10.10.100.209');

  // SIMPLE TIME CONTEXT
  $hour = date('H') + 0;
  $minute = date('i') + 0;
  if (($hour > 21 || $hour < 8) && !isset($_GET['force'])) {
    echo json_encode(['status' => false, 'data' => ['message' => 'Not a valid time']]);
    die();
  }

  // CHECK BASIC PARAMS
  if (!isset($_GET['fungsi']) || !isset($_GET['id_device']) || !isset($_GET['jml_arg'])) {
    echo json_encode(['status' => false, 'data' => ['message' => 'Bad request']]);
    die();
  }

  $jml_arg = [
    'set_mute' => 1,
    'set_action' => 1,
    'set_sound_volume' => 1,
    'set_file_presentasi' => 1
  ];

  $allowed_file = ['application/pdf', 'application/vnd.ms-powerpoint', 'video/mp4'];

  $i = $jml_arg[_GET['fungsi']];
  while ($i > 0) {
    $_ = 'arg' . $i;
    if (!isset($_GET[$_])) {
      echo json_encode(['status' => false, 'data' => ['message' => 'Jumlah argumen tidak sesuai']]);
      die();
    }

    $i--;
  }

  // JUST TAKE IT
  $id = $_GET['id_device'];
  if ($id + 0 < 0 || $id + 0 > MAX_DEVICE) {
    echo json_encode(['status' => false, 'data' => ['message' => 'Invalid id_device']]);
    die();
  }

  // MAIN FUNCTION
  switch ($_GET['fungsi']) {
    // http://localhost/index.php?fungsi=set_file_presentasi&jml_arg=1&id_device=1&arg1=http://www.axmag.com/download/pdfurl-guide.pdf
    case 'set_file_presentasi':
      $url = $_GET["arg1"];
      if ($url != 'null') {
        $header = get_headers($url, true);

        // CHECK IF GIVEN URL IS PUBLIC ACCESSED
        if (strpos($header[0], '200') === false) {
          echo json_encode(['status' => false, 'data' => ['message' => 'Not a valid url']]);
          die();
        }

        // CHECK FILE TYPE
        if (!in_array($header['Content-Type'], $allowed_file)) {
          echo json_encode(['status' => false, 'data' => ['message' => 'Not allowed file type (' . $header['Content-Type'] . ')']]);
          die();
        }
      }

      if (true) {
        $response = sendRequest(KELOMPOK_JENDELA, 'getLux', 1, []);
        if (!is_null($response)) {
          if ($response->data->current_lux + 0 > 10000) {
            sendRequest(KELOMPOK_JENDELA, 'setCurtainStatus', 1, [1 => 'close']);
            sendRequest(KELOMPOK_LAMPU, 'set_lampu', 1, [1 => 'mati']);

          } else if ($response->data->current_lux + 0 > 1000) {
            sendRequest(KELOMPOK_LAMPU, 'set_lampu', 1, [1 => 'redup']);
          }
        }

        $client->set(PRESENTATION_FILE.$id, $url);
        $client->set(PRESENTATION_ACTION.$id, 'stop');

        echo json_encode(['status' => true, 'data' => ['nama_file' => $url]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }

      break;

    // http://localhost/index.php?fungsi=set_action&jml_arg=1&id_device=1&arg1=play
    case 'set_action':
      $action = $_GET['arg1'];
      if ($action === 'play' || $action === 'pause' || $action === 'stop' || $action === 'next' || $action === 'prev' || $action === 'null') {
        $nama_file = $client->get(PRESENTATION_FILE.$id);
        $client->set(PRESENTATION_ACTION.$id, $action);

    		if($action === 'stop'){
    			sendRequest(KELOMPOK_JENDELA, 'setCurtainStatus', 1, [1 => 'open']);
          sendRequest(KELOMPOK_LAMPU, 'set_lampu', 1, [1 => 'hidup']);
    		}

        echo json_encode(['status' => true, 'data' => ['nama_file' => $nama_file, 'action' => $action]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'Bad command']]);
      }
      break;

    // http://localhost/index.php?fungsi=set_sound_volume&jml_arg=1&id_device=1&arg1=76
    case 'set_sound_volume':
      $action = 0 + $_GET['arg1'];
      $before = $client->get(SOUND_VOLUME.$id);
      $client->set(SOUND_VOLUME.$id, $action);

      if (true) {
        echo json_encode(['status' => true, 'data' => ['volume_now' => $action, 'volume_before' => $before + 0]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }
      break;

    // http://localhost/index.php?fungsi=set_mute&jml_arg=1&id_device=1&arg1=true
    case 'set_mute':
      $action = $_GET['arg1'];
      if ($action === 'true' || $action === 'false') {
        $before = $client->get(SOUND_MUTE.$id);
        $client->set(SOUND_MUTE.$id, $action);

        echo json_encode(['status' => true, 'data' => ['status_now' => $action === 'true' ? true : false, 'status_before' => $before === 'true' ? true : false]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'Bad command']]);
      }

      break;

    // http://localhost/index.php?fungsi=get_file_presentasi&jml_arg=0&id_device=1
    case 'get_file_presentasi':
      $file_path = $client->get(PRESENTATION_FILE.$id);

      if (true) {
        echo json_encode(['status' => true, 'data' => ['nama_file' => $file_path]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }
      break;

    // http://localhost/index.php?fungsi=get_action&jml_arg=0&id_device=1
    case 'get_action':
      $nama_file = $client->get(PRESENTATION_FILE.$id);
      $action = $client->get(PRESENTATION_ACTION.$id);

      if (true) {
        echo json_encode(['status' => true, 'data' => ['nama_file' => $nama_file, 'action' => $action]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }
      break;

    // http://localhost/index.php?fungsi=get_sound_status&jml_arg=0&id_device=1
    case 'get_sound_status':
      $volume = $client->get(SOUND_VOLUME.$id);
      $mute = $client->get(SOUND_MUTE.$id);

      if (true) {
        echo json_encode(['status' => true, 'data' => ['volume' => $volume + 0, 'mute' => $mute === 'true' ? true : false]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }
      break;

    // http://localhost/index.php?fungsi=get_status&jml_arg=0&id_device=1
    case 'get_status':
      $action = $client->get(PRESENTATION_ACTION.$id);
      $file = $client->get(PRESENTATION_FILE.$id);
      $volume = $client->get(SOUND_VOLUME.$id);
      $mute = $client->get(SOUND_MUTE.$id);

      if (true) {
        echo json_encode(['status' => true, 'data' => ['file' => $file, 'action' => $action, 'volume' => $volume + 0, 'mute' => $mute === 'true' ? true : false]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }
      break;

    default:
      echo json_encode(['status' => false, 'data' => ['message' => 'Unrecognized command']]);
      break;
  }
?>
