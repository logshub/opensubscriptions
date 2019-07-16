<?php
namespace OpenSubscriptions\OpenSubscriptions\Model;

interface SubmoduleConnectionInterface
{
    public function getSettingsDefinitions(): array;
}
