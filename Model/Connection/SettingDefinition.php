<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\Connection;

class SettingDefinition
{
    protected $name;
    protected $label;
    protected $note;
    
    public function __construct($name, $label, $note = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->note = $note;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function getNote()
    {
        return $this->note;
    }
}