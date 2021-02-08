<?php

/*function getResponse($url,$data){
    $options = array(
        'http' => array(
          'method'  => 'POST',
          'content' => json_encode( $data ),
          'header'=>  "Content-Type: application/json\r\n" .
                      "Accept: application/json\r\n"
          )
      );



$context  = stream_context_create( $options );
$result = file_get_contents( $url, false, $context );
$response = json_decode( $result );

return $response;
}*/

function getResponse2($url,$data){
    ini_set('allow_url_fopen',1);
    $result = file_get_contents("/api/diamond/login");
    //$response = json_decode( $result );
    return $result;
}

?>
