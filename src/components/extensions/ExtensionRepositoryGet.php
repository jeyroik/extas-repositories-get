<?php
namespace extas\components\extensions;

use extas\components\SystemContainer;
use extas\interfaces\extensions\IExtensionRepositoryGet;

/**
 * Class ExtensionRepositoryGet
 *
 * @package extas\components\extensions
 * @author jeyroik@gmail.com
 */
class ExtensionRepositoryGet extends Extension implements IExtensionRepositoryGet
{
    /**
     * @param string $methodName
     * @param mixed ...$args
     * @return mixed
     */
    protected function wildcardMethod(string $methodName, ...$args)
    {
        return SystemContainer::getItem($methodName);
    }
}
