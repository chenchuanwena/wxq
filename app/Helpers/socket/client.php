<?php

namespace App\Helpers\socket;

use Illuminate\Support\Facades\Log;
// error_reporting(E_ALL);
// ini_set('display_errors', 'on');
require __DIR__ . '/websocket_client.php';
error_reporting(0);
class client
{
  private $socketHost = null;
  private $redisHost = null;
  private $socketPort = 9501;
  private $redisPort = 6379;
  public function __construct()
  {
    $this->socketHost = env('GUITAR_SOCKET_HOST');
    $this->redisHost = env('GUITAR_REDIS_HOST');
    $this->socketPort = env('GUITAR_SOCKET_PORT');
    $this->redisPort = env('GUITAR_REDIS_PORT');
  }
  public function pushToClient($uidkey, $type, $message)
  {



    $host = $this->socketHost;
    $prot = $this->socketPort;
    $config = array(
      'redisHost' => $this->redisHost,
      'redisPort' => $this->redisPort,
      'socketHost' => $this->socketHost,
      'socketPort' => $this->socketPort,

    );
    Log::info('socket config is:' . json_encode($config, JSON_UNESCAPED_UNICODE));
    $redis = new \Redis();
    $redis->connect($this->redisHost, $this->redisPort);

    $res = $redis->hmget($uidkey, array('token', 'fd'));


    $client = new \WebSocketClient($host, $prot);
    $data = $client->connect();

    $sendData = array(
      'toFd' => $res['fd'],
      'message' => $message,
      'type' => $type,
      'status' => 'success',
    );

    $client->send(json_encode($sendData));
    $recvData = "";
    while (1) {
      $tmp = $client->recv();
      if (empty($tmp)) {
        break;
      }
      $recvData .= $tmp;
    }
  }
}
