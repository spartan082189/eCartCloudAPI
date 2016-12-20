<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../vendor/facebook/graph-sdk/src/Facebook/autoload.php';


$app = new \Slim\App;

$app->get('/login/{token}', function (Request $request, Response $response) {
    $fb = new Facebook\Facebook([
      'app_id'                => '1715370978779177',
      'app_secret'            => 'dc8eed01273932fb2319904cff7fc456',
      'default_graph_version' => 'v2.8',
    ]);
    $token = $request->getAttribute('token');
    try {
      $res = $fb->get('/me?fields=id,name,email,first_name,gender,last_name,timezone', $token);
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
    }
    $decodedBody = $res->getDecodedBody();
    return $response->withHeader('Content-Type', 'application/json')->withJson($decodedBody);
});
$app->run();

?>
