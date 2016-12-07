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
  const SOUND_VOLUME = 'VOLUME';
  const SOUND_MUTE = 'MUTE';
  const PRESENTATION_ACTION = 'ACTION';
  const PRESENTATION_FILE = 'FILE';

  // EXAMPLE
  // http://[BASE_URL]:[BASE_PORT]/index.php?fungsi=set_pintu&id_device=1&jml_arg=1&arg1=buka

  // CHECK BASIC PARAMS
  if (!isset(_GET['fungsi']) || !isset($_GET['id_device']) || !isset($_GET['jml_arg'])) {
    echo json_encode(['status' => false, 'data' => ['message' => 'Bad request']]);
    die();
  }

  $jml_arg = [
    'set_mute' => 1,
    'set_sound_volume' => 1
  ];

  $i = $jml_arg[_GET['fungsi']];
  while ($i > 0) {
    $_ = 'arg' . $i
    if (!isset($_GET[$_])) {
      echo json_encode(['status' => false, 'data' => ['message' => 'Jumlah argumen tidak sesuai']]);
      die();
    }

    $i--;
  }

  // JUST TAKE IT
  $id = $_GET['id_device'];

  // MAIN FUNCTION
  switch ($_GET['fungsi']) {
    case 'set_file_presentasi':
      $uploaded = $_FILES["uploadedFile"];

      if ($uploadedFile != null) {
        // TODO PENGECEKAN

        $file_name = $uploaded["name"];
        $file_upload = file_get_contents($uploaded["tmp_name"]);
        $file_path = '/tmp/'.$file_name;

        $client->set(PRESENTATION_FILE, $file_name);
        file_put_contents($file_path, $file_upload);

        echo json_encode(['status' => true, 'data' => ['message' => 'success', 'nama_file' => $file_path]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }

      break;

    case 'set_action':
      $action = $_GET['arg1'];
      if ($action === 'play' || $action === 'pause' || $action === 'stop' || $action === 'next' || $action === 'prev') {
        $nama_file = $client->get(PRESENTATION_FILE);
        $client->set(PRESENTATION_ACTION, $action);

        // TODO CHECK IF NAMA_FILE NULL
        echo json_encode(['status' => true, 'data' => ['nama_file' => $nama_file, 'action' => $action]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'Bad command']]);
      }
      break;

    case 'set_sound_volume':
      $action = 0 + $_GET['arg1'];
      $before = $client->get(SOUND_VOLUME);
      $client->set(SOUND_VOLUME, $action);

      if ($aman) {
        echo json_encode(['status' => true, 'data' => ['volume_now' => $action, 'volume_before' => $before]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }
      break;

    case 'set_mute':
      $action = $_GET['arg1'];
      if ($action === 'true' || $action === 'false') {
        $before = $client->get(SOUND_MUTE);
        $client->set(SOUND_MUTE, $action);

        // TODO CHECK IF NOT SAFE
        echo json_encode(['status' => true, 'data' => ['status_now' => $action, 'status_before' => $before]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'Bad command']]);
      }

      break;

    case 'get_file_presentasi':
      $file_path = $client->get(PRESENTATION_FILE);

      if ($aman) {
        echo json_encode(['status' => true, 'data' => ['file' => $file_path]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }
      break;

    case 'get_action':
      $nama_file = $client->get(PRESENTATION_FILE);
      $action = $client->get(PRESENTATION_ACTION);

      // TODO CHECK SOMETHING

      if ($aman) {
        echo json_encode(['status' => true, 'data' => ['nama_file' => $nama_file, 'action' => $action]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }
      break;

    case 'get_sound_status':
      $volume = $client->get(SOUND_VOLUME);
      $mute = $client->get(SOUND_MUTE);

      // TODO CHECK SOMETHING

      if ($aman) {
        echo json_encode(['status' => true, 'data' => ['volume' => $volume, 'mute' => $mute]]);
      } else {
        echo json_encode(['status' => false, 'data' => ['message' => 'An non-obvious error occured']]);
      }
      break;

    default:
      echo json_encode(['status' => false, 'data' => ['message' => 'Unrecognized command']]);
      break;
  }
?>
