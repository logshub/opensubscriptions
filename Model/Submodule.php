<?php
namespace Logshub\OpenSubscriptions\Model;

abstract class Submodule
{
    protected static $submodules = [];
    protected static $submodulesInstances = [];

    public static function all()
    {
        if (count(self::$submodulesInstances) === 0) {
            foreach (self::$submodules as $submodule) {
                $obj = new $submodule;
                if ($obj instanceof SubmoduleInterface) {
                    self::$submodulesInstances[$submodule] = $obj;
                }
            }
        }

        return self::$submodulesInstances;
    }

    public static function allIds(): array
    {
        $all = [];
        foreach (self::all() as $submodule) {
            $all[] = $submodule->getId();
        }

        return $all;
    }

    public static function register(string $modulename)
    {
        self::$submodules[] = $modulename;
    }
    
    /**
     * 
     * @param string $code
     * @return Submodule
     * @throws SubmoduleException
     */
    public static function get(string $code): SubmoduleInterface
    {
        foreach (self::all() as $submodule) {
            if ($code === $submodule->getId()) {
                return $submodule;
            }
        }
        
        $msg = 'Submodule '.$code.' not found. Available: ' .
            implode(', ', Submodule::allIds());

        throw new \Logshub\OpenSubscriptions\Exception\SubmoduleException($msg);
    }
}
