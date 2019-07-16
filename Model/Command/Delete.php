<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\Command;

use OpenSubscriptions\OpenSubscriptions\Model\CommandInterface;
use OpenSubscriptions\OpenSubscriptions\Exception\CommandException;
use OpenSubscriptions\OpenSubscriptions\Model\Service;

/**
 * Basic command that works without external service
 */
class Delete implements CommandInterface
{
    public function isExecutable(Service $service): bool
    {
        return $service->getStatus() !== Service::DELETED && $service->getIsCreated();
    }

    public function exec(Service $service): bool
    {
        try {
            // $connection->addLog($service, 'delete', $response->getStatusCode(), '', $response->getBody());
            $service->setStatus(Service::DELETED);
            $service->save();

            return true;
        } catch (\Exception $e) {
            // $connection->addLog($service, 'delete', 0, '', $msg);
            throw new CommandException('Unable to delete: ' . $e->getMessage());
        }
    }

    public function getHelp(): string
    {
        return 'Changes service\'s status to deleted';
    }
}