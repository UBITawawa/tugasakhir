<?php
  // JSON HEADER
  header('Content-type: application/json');

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
  // LIKE SOUND_VOLUME.$ID_DEVICE


  // EXAMPLE
  // http://[BASE_URL]:[BASE_PORT]/index.php?fungsi=set_pintu&id_device=1&jml_arg=1&arg1=buka

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
      $header = get_headers($url, true);

      if (strpos($header[0], '200') === false) {
        echo json_encode(['status' => false, 'data' => ['message' => 'Not a valid url']]);
        die();
      }

      if (in_array($header['Content-Type'], $allowed_file)) {
        echo json_encode(['status' => false, 'data' => ['message' => 'Not allowed file type']]);
        die();
      }

      if (true) {
        $client->set(PRESENTATION_FILE.$id, $url);
        echo json_encode(['status' => true, 'data' => ['message' => 'success', 'nama_file' => $url]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }

      break;

    // http://localhost/index.php?fungsi=set_action&jml_arg=1&id_device=1&arg1=play
    case 'set_action':
      $action = $_GET['arg1'];
      if ($action === 'play' || $action === 'pause' || $action === 'stop' || $action === 'next' || $action === 'prev') {
        $nama_file = $client->get(PRESENTATION_FILE.$id);
        $client->set(PRESENTATION_ACTION.$id, $action);

        // TODO CHECK IF NAMA_FILE NULL
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

        // TODO CHECK IF NOT SAFE
        echo json_encode(['status' => true, 'data' => ['status_now' => $action === 'true' ? true : false, 'status_before' => $before === 'true' ? true : false]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'Bad command']]);
      }

      break;

    // http://localhost/index.php?fungsi=get_file_presentasi&jml_arg=0&id_device=1
    case 'get_file_presentasi':
      $file_path = $client->get(PRESENTATION_FILE.$id);

      if (true) {
        echo json_encode(['status' => true, 'data' => ['file' => $file_path]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }
      break;

    // http://localhost/index.php?fungsi=get_action&jml_arg=0&id_device=1
    case 'get_action':
      $nama_file = $client->get(PRESENTATION_FILE.$id);
      $action = $client->get(PRESENTATION_ACTION.$id);

      // TODO CHECK SOMETHING

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

      // TODO CHECK SOMETHING

      if (true) {
        echo json_encode(['status' => true, 'data' => ['volume' => $volume + 0, 'mute' => $mute === 'true' ? true : false]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }
      break;

    default:
      echo json_encode(['status' => false, 'data' => ['message' => 'Unrecognized command']]);
      break;
  }
?>
