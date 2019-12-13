<?php
namespace Logshub\OpenSubscriptions\Model;

interface SubmoduleConnectionInterface
{
    public function getSettingsDefinitions(): array;
}
