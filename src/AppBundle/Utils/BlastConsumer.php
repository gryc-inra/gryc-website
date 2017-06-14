<?php

namespace AppBundle\Utils;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class BlastConsumer implements ConsumerInterface
{
    private $blastManager;

    public function __construct(BlastManager $blastManager)
    {
        $this->blastManager = $blastManager;
        echo date('d/m/Y H:i:s'),' [*] Waiting for messages. To exit press CTRL+C', PHP_EOL, PHP_EOL;
    }

    public function execute(AMQPMessage $msg)
    {
        echo date('d/m/Y H:i:s'), ' [x] Received ', $msg->body, "\n";
        // Blast the job
        $this->blastManager->blast($msg->body);
        echo date('d/m/Y H:i:s'),' [x] Done ', "\n";
    }
}
