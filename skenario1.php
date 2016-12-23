<pre>
  <?php
    require('Utils.php');

echo <<<DESCRIPTION
<h1>
SCENARIO #1
TEST BASIC FUNCTION
</h1>
DESCRIPTION;

    // send request to set presentation
    pretty_print('http://localhost/ubi/?fungsi=set_file_presentasi&jml_arg=1&id_device=1&arg1=http://www.axmag.com/download/pdfurl-guide.pdf');
    $response = sendRequest('localhost/ubi', 'set_file_presentasi', 1, [1 => 'http://www.axmag.com/download/pdfurl-guide.pdf']);
    pretty_print($response);

    // send request to set volume
    pretty_print('http://localhost/ubi/?fungsi=set_sound_volume&jml_arg=1&id_device=1&arg1=76');
    $response = sendRequest('localhost/ubi', 'set_sound_volume', 1, [1 => 76]);
    pretty_print($response);

    // send request to set mute
    pretty_print('http://localhost/ubi/?fungsi=set_mute&jml_arg=1&id_device=1&arg1=true');
    $response = sendRequest('localhost/ubi', 'set_mute', 1, [1 => 'true']);
    pretty_print($response);

    // send request to set action
    pretty_print('http://localhost/ubi/?fungsi=set_action&jml_arg=1&id_device=1&arg1=play');
    $response = sendRequest('localhost/ubi', 'set_action', 1, [1 => 'play']);
    pretty_print($response);

  ?>
</pre>
