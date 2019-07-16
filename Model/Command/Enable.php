<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\Command;

use OpenSubscriptions\OpenSubscriptions\Model\CommandInterface;
use OpenSubscriptions\OpenSubscriptions\Exception\CommandException;
use OpenSubscriptions\OpenSubscriptions\Model\Service;

/**
 * Basic command that works without external service
 */
class Enable implements CommandInterface
{
    public function isExecutable(Service $service): bool
    {
        return $service->getStatus() === Service::DISABLED && $service->getIsCreated();
    }

    public function exec(Service $service): bool
    {
        try {
            // $connection->addLog($service, 'disable', $response->getStatusCode(), '', $response->getBody());
            $service->setStatus(Service::ACTIVE);
            $service->save();

            return true;
        } catch (\Exception $e) {
            // $connection->addLog($service, 'disable', 0, '', $msg);
            throw new CommandException('Unable to enable: ' . $e->getMessage());
        }
    }

    public function getHelp(): string
    {
        return 'Changes service\'s status to enabled';
    }
}