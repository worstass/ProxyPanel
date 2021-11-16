<?php

require __DIR__ . "/vendor/autoload.php";

use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

$relay = new Spiral\Goridge\StreamRelay(STDIN, STDOUT);
$psr7 = new Spiral\RoadRunner\PSR7Client(new Spiral\RoadRunner\Worker($relay));

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

while ($req = $psr7->acceptRequest()) {
    try {
        $httpFoundationFactory = new HttpFoundationFactory();
        if (strpos($req->getHeaderLine("content-type"), "application/json") === 0) {
            $body = $req->getBody();
            $parsedBody = json_decode($body, true);
            $req = $req->withParsedBody($parsedBody);
        }

        $symfonyReq = $httpFoundationFactory->createRequest($req);
        $request = Illuminate\Http\Request::createFromBase($symfonyReq);
        $response = $kernel->handle($request);
        $psr7factory = new DiactorosFactory();
        $psr7response = $psr7factory->createResponse($response);
        $psr7->respond($psr7response);
    } catch (\Throwable $e) {
        $psr7->getWorker()->error((string)$e);
    }
}
