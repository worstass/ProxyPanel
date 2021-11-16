<?php
/**
 * Sample GRPC PHP server.
 */
namespace Service;

use Spiral\GRPC\ContextInterface;

class EchoService implements EchoInterface
{
    public function Ping(ContextInterface $ctx, Message $in): Message
    {
        $out = new Message();
        return $out->setMsg(strtoupper($in->getMsg()));
    }
}
