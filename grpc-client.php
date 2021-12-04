<?php

use Grpc\ChannelCredentials;
use Proto\Echo\EchoClient;
use Proto\Echo\Message;

require_once __DIR__ . '/vendor/autoload.php';

$client = new EchoClient(
    'localhost:9001',
    [
        'credentials' => ChannelCredentials::createInsecure(),
    ]
);

/** @var Message $response */
[$response, $status] = $client->Ping(
    new Message(['msg' => $argv[1]])
)->wait();

echo $response->getMsg();
