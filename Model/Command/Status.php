<?php
namespace Logshub\OpenSubscriptions\Model\Command;

use Logshub\OpenSubscriptions\Model\CommandInterface;
use Logshub\OpenSubscriptions\Exception\CommandException;
use Logshub\OpenSubscriptions\Model\Service;

/**
 * Basic command that works without external service
 */
class Status implements CommandInterface
{
    public function isExecutable(Service $service): bool
    {
        return true;
    }

    public function exec(Service $service): bool
    {
        throw new CommandException('Unable to check status - no remote connection');
    }

    public function getHelp(): string
    {
        return 'Checks status on remote service';
    }
}