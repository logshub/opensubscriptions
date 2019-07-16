<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\Command;

use OpenSubscriptions\OpenSubscriptions\Model\CommandInterface;
use OpenSubscriptions\OpenSubscriptions\Exception\CommandException;
use OpenSubscriptions\OpenSubscriptions\Model\Service;

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