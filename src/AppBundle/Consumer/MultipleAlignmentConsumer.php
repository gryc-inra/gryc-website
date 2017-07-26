<?php

namespace AppBundle\Consumer;

use AppBundle\Utils\MultipleAlignmentManager;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class MultipleAlignmentConsumer implements ConsumerInterface
{
    private $multipleAlignmentManager;

    public function __construct(MultipleAlignmentManager $multipleAlignmentManager)
    {
        $this->multipleAlignmentManager = $multipleAlignmentManager;
        echo date('d/m/Y H:i:s'),' [*] Waiting for messages. To exit press CTRL+C', PHP_EOL, PHP_EOL;
    }

    public function execute(AMQPMessage $msg)
    {
        echo date('d/m/Y H:i:s'), ' [x] Received ', $msg->body, "\n";
        // Execute the multiple alignment
        $this->multipleAlignmentManager->align($msg->body);
        echo date('d/m/Y H:i:s'),' [x] Done ', "\n";
    }
}
