<?php
namespace Logshub\OpenSubscriptions\Model;

use Logshub\OpenSubscriptions\Model\SubmoduleInterface;
use Logshub\OpenSubscriptions\Model\CommandInterface;
use Logshub\OpenSubscriptions\Model\Connection as OaConnection;
use Logshub\OpenSubscriptions\Model\SubmoduleConnectionInterface;

abstract class SubmoduleAbstract implements SubmoduleInterface
{
    public function getCreateCommand(): CommandInterface
    {
        return new \Logshub\OpenSubscriptions\Model\Command\Create();
    }

    public function getDeleteCommand(): CommandInterface
    {
        return new \Logshub\OpenSubscriptions\Model\Command\Delete();
    }

    public function getEnableCommand(): CommandInterface
    {
        return new \Logshub\OpenSubscriptions\Model\Command\Enable();
    }

    public function getDisableCommand(): CommandInterface
    {
        return new \Logshub\OpenSubscriptions\Model\Command\Disable();
    }

    public function getResetCredentialsCommand(): CommandInterface
    {
        return new \Logshub\OpenSubscriptions\Model\Command\ResetCredentials();
    }

    public function getStatusCommand(): CommandInterface
    {
        return new \Logshub\OpenSubscriptions\Model\Command\Status();
    }

    public function getHelpBlock(): string
    {
        return '';
    }
}