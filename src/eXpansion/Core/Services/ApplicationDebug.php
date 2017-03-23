<?php

namespace eXpansion\Core\Services;

use eXpansion\Core\Services\Application\AbstractApplication;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

/**
 * eXpansion Application main routine.
 *
 * @package eXpansion\Core\Services
 */
class ApplicationDebug extends AbstractApplication
{

    protected function executeRun()
    {

        $calls = $this->connection->executeCallbacks();
        if (!empty($calls)) {
            foreach ($calls as $call) {
                $method = preg_replace('/^[[:alpha:]]+\./', '', $call[0]); // remove trailing "Whatever."
                $params = (array) $call[1];

                $this->dispatcher->dispatch($method, $params);
            }
        }
        $this->connection->executeMulticall();
    }
}
