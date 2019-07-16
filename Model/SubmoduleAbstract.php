<?php
namespace OpenSubscriptions\OpenSubscriptions\Model;

use OpenSubscriptions\OpenSubscriptions\Model\SubmoduleInterface;
use OpenSubscriptions\OpenSubscriptions\Model\CommandInterface;
use OpenSubscriptions\OpenSubscriptions\Model\Connection as OaConnection;
use OpenSubscriptions\OpenSubscriptions\Model\SubmoduleConnectionInterface;

abstract class SubmoduleAbstract implements SubmoduleInterface
{
    public function getCreateCommand(): CommandInterface
    {
        return new \OpenSubscriptions\OpenSubscriptions\Model\Command\Create();
    }

    public function getDeleteCommand(): CommandInterface
    {
        return new \OpenSubscriptions\OpenSubscriptions\Model\Command\Delete();
    }

    public function getEnableCommand(): CommandInterface
    {
        return new \OpenSubscriptions\OpenSubscriptions\Model\Command\Enable();
    }

    public function getDisableCommand(): CommandInterface
    {
        return new \OpenSubscriptions\OpenSubscriptions\Model\Command\Disable();
    }

    public function getResetCredentialsCommand(): CommandInterface
    {
        return new \OpenSubscriptions\OpenSubscriptions\Model\Command\ResetCredentials();
    }

    public function getStatusCommand(): CommandInterface
    {
        return new \OpenSubscriptions\OpenSubscriptions\Model\Command\Status();
    }

    public function getHelpBlock(): string
    {
        return '';
    }
}