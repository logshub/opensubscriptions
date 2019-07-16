<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\Command;

use OpenSubscriptions\OpenSubscriptions\Model\CommandInterface;
use OpenSubscriptions\OpenSubscriptions\Exception\CommandException;
use OpenSubscriptions\OpenSubscriptions\Model\Service;

/**
 * Basic command that works without external service
 */
class Create implements CommandInterface
{
    public function isExecutable(Service $service): bool
    {
        if ($service->getStatus() !== Service::PENDING || $service->getIsCreated()) {
            return false;
        }

        return true;
    }

    public function exec(Service $service): bool
    {
        try {
            // TODO: log to service, not connection
            // $connection = $service->getConnection();
            // $connection->addLog($service, 'create', $response->getStatusCode(), '', $response->getBody());
            $service->setStatus(Service::ACTIVE);
            $service->setIsCreated(true);
            $service->save();

            return true;
        } catch (\Exception $e) {
            // $connection->addLog($service, 'create', 0, '', $e->getMessage());
            throw new CommandException('Unable to create: ' . $e->getMessage());
        }
    }

    public function getHelp(): string
    {
        return 'Changes service\'s status to active';
    }
}