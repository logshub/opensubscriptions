<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\Command;

use OpenSubscriptions\OpenSubscriptions\Model\CommandInterface;
use OpenSubscriptions\OpenSubscriptions\Exception\CommandException;
use OpenSubscriptions\OpenSubscriptions\Model\Service;

/**
 * Basic command that works without external service
 */
class ResetCredentials implements CommandInterface
{
    public function isExecutable(Service $service): bool
    {
        return $service->getStatus() !== Service::DELETED &&
            $service->getIsCreated() &&
            $service->getExternalId();
    }

    public function exec(Service $service): bool
    {
        try {
            // $connection->addLog($service, 'resetauth', $response->getStatusCode(), '', $response->getBody());
            $service->setPassword($this->getRandomStr(10));
            $service->save();

            return true;
        } catch (\Exception $e) {
            // $connection->addLog($service, 'resetauth', 0, '', $msg);
            throw new CommandException('Unable to reset auth: ' . $e->getMessage());
        }
    }

    public function getHelp(): string
    {
        return 'Resets service\'s password';
    }

    protected function getRandomStr($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = \mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[\random_int(0, $max)];
        }
        return \implode('', $pieces);
    }
}