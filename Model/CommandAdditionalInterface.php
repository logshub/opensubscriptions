<?php
namespace OpenSubscriptions\OpenSubscriptions\Model;

interface CommandAdditionalInterface extends CommandInterface
{
    /**
     * Returns unique id of command
     * @return string
     */
    public function getId(): string;

    /**
     * Returns name of command
     * @return string
     */
    public function getName(): string;
}
