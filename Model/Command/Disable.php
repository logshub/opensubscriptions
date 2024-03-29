<?php
namespace Logshub\OpenSubscriptions\Model\Command;

use Logshub\OpenSubscriptions\Model\CommandInterface;
use Logshub\OpenSubscriptions\Exception\CommandException;
use Logshub\OpenSubscriptions\Model\Service;

/**
 * Basic command that works without external service
 */
class Disable implements CommandInterface
{
    public function isExecutable(Service $service): bool
    {
        return $service->getStatus() === Service::ACTIVE && $service->getIsCreated();
    }

    public function exec(Service $service): bool
    {
        try {
            // $connection->addLog($service, 'disable', $response->getStatusCode(), '', $response->getBody());
            $service->setStatus(Service::DISABLED);
            $service->save();

            return true;
        } catch (\Exception $e) {
            // $connection->addLog($service, 'disable', 0, '', $msg);
            throw new CommandException('Unable to disable: ' . $e->getMessage());
        }
    }

    public function getHelp(): string
    {
        return 'Changes service\'s status to disabled';
    }
}