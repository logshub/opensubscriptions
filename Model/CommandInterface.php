<?php
namespace OpenSubscriptions\OpenSubscriptions\Model;

interface CommandInterface
{
    /**
     * Execution of this command.
     * Should return true, or throw exception
     * @return bool
     * @throws OpenSubscriptions\OpenSubscriptions\Exception\CommandException
     */
    public function exec(Service $service): bool;

    /**
     * Returns bool if command could be executed,
     * eg. do not create if already created
     * @return bool
     */
    public function isExecutable(Service $service): bool;

    /**
     * Returns help string, visible next to the command button on service's page
     * @return string
     */
    public function getHelp(): string;
}
