<?php

use Spiral\RoadRunner;
use Nyholm\Psr7;
use Spiral\Goridge\StreamRelay;
use Spiral\RoadRunner\Worker;

include "vendor/autoload.php";

$worker = new Worker(new StreamRelay(STDIN, STDOUT));
$psrFactory = new Psr7\Factory\Psr17Factory();

$psr7 = new PSR7Worker($worker, $psrFactory, $psrFactory, $psrFactory);

while (true) {
    try {
        $request = $psr7->waitRequest();

        if (!($request instanceof \Psr\Http\Message\ServerRequestInterface)) { // Termination request received
            break;
        }
    } catch (\Throwable $e) {
        $psr7->respond(new Psr7\Response(400)); // Bad Request
        continue;
    }

    try {
        // Application code logic
        $psr7->respond(new Psr7\Response(200, [], 'Hello RoadRunner!'));
    } catch (\Throwable $e) {
        $psr7->respond(new Psr7\Response(500, [], 'Something Went Wrong!'));
    }
}
