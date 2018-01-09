<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace AppBundle\Consumer;

use AppBundle\Service\MultipleAlignmentManager;
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
