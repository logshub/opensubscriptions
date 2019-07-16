<?php
namespace OpenSubscriptions\OpenSubscriptions\Model;

interface SubmoduleInterface
{
    public function getId(): string;

    public function getCreateCommand(): CommandInterface;

    public function getDeleteCommand(): CommandInterface;

    public function getEnableCommand(): CommandInterface;

    public function getDisableCommand(): CommandInterface;

    public function getResetCredentialsCommand(): CommandInterface;

    public function getStatusCommand(): CommandInterface;
    
    public function getConnection(Connection $connection): SubmoduleConnectionInterface;

    /**
     * Returns array of CommandAdditionalInterface obejcts
     * @return array
     */
    public function getAdditionalCommands(): array;

    public function getHelpBlock(): string;
}
