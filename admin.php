<?php
  require('TinyRedisClient.php');
  $client = new TinyRedisClient('localhost:6379');

  define(PRESENTATION_ACTION, 'ACTION');
  define(PRESENTATION_FILE, 'FILE');
  define(SOUND_VOLUME, 'VOLUME');
  define(SOUND_MUTE, 'MUTE');
  define(DEVICES, 3);

  if (isset($_GET['id_device'])) {
    $client->set(PRESENTATION_ACTION.$_GET['id_device'], $_GET['action']);
    $client->set(PRESENTATION_FILE.$_GET['id_device'], $_GET['file']);
    $client->set(SOUND_VOLUME.$_GET['id_device'], $_GET['volume']);
    $client->set(SOUND_MUTE.$_GET['id_device'], isset($_GET['mute']) ? 'true' : 'false');
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Control Panel (Admin Only!!)</title>
</head>
<body>
  <?php for ($i=1; $i <= DEVICES; $i++) {  ?>
    <h1>Device #<?= $i ?></h1>
    <form action="">
      FILE:
        <input type="text" name="file" value=<?= $client->get(PRESENTATION_FILE.$i) ?>>

      ACTION:
        <select name="action">
          <option value="standby" <?= $client->get(PRESENTATION_ACTION.$i) === null ? 'selected' : '' ?>>standby</option>
          <option value="play" <?= $client->get(PRESENTATION_ACTION.$i) === 'play' ? 'selected' : '' ?>>play</option>
          <option value="pause" <?= $client->get(PRESENTATION_ACTION.$i) === 'pause' ? 'selected' : '' ?>>pause</option>
          <option value="stop" <?= $client->get(PRESENTATION_ACTION.$i) === 'stop' ? 'selected' : '' ?>>stop</option>
          <option value="next" <?= $client->get(PRESENTATION_ACTION.$i) === 'next' ? 'selected' : '' ?>>next</option>
          <option value="prev" <?= $client->get(PRESENTATION_ACTION.$i) === 'prev' ? 'selected' : '' ?>>prev</option>
        </select>

      VOLUME:
        <input type="number" name="volume" value=<?= $client->get(SOUND_VOLUME.$i) ?>>

      MUTE:
        <input type="checkbox" name="mute" <?= $client->get(SOUND_MUTE.$i) === 'true' ? 'checked' : '' ?>>

      <input type="hidden" name="id_device" value=<?= $i ?>>
      <p><input type="submit" value="Submit"></p>
    </form>
    <hr>
  <?php } ?>
</body>
</html>
