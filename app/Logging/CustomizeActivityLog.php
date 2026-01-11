<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;

class CustomizeActivityLog
{
    /**
     * Customize the given logger instance.
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            // set JSON formatter for file handler
            $handler->setFormatter(new JsonFormatter());
        }
    }
}
