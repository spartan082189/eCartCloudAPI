<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use GuzzleHttp\Exception\RequestException;

require '../vendor/autoload.php';
require '../vendor/facebook/graph-sdk/src/Facebook/autoload.php';


$app = new \Slim\App;

//----------------------------------------
// Login method - /login/{token}
// ---------------------------------------
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
    return $response->withHeader('Content-Type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*')->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')->withJson($decodedBody);
});

//----------------------------------------
// Products method - /search/{query}
// ---------------------------------------
$app->get('/search/{query}', function(Request $request, Response $response) {
  try {
    $client = new GuzzleHttp\Client([
    // Base URI
    'base_uri' => 'http://api.walmartlabs.com/v1/'
    ]);
    $token = '8hmm66575ju4bkvjzc8cbca5';
    $res = $client->request('GET', 'search?query='.$request->getAttribute('query').'&format=json&apiKey='.$token);
    $json = json_decode($res->getBody()->getContents());
    return $response->withHeader('Content-Type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*')->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')->withJson($json);
  } catch (RequestException $e) {
    echo 'Walmart API returned an error: ' . $e->getMessage();
      exit;
  }
});

$app->run();

?>
