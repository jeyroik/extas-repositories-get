<?php
namespace extas\components;

/**
 * Trait THasMagicClass
 *
 * @package extas\components
 * @author jeyroik <jeyroik@gmail.com>
 */
trait THasMagicClass
{
    /**
     * @param string $name
     * @return mixed
     */
    protected function getMagicClass(string $name)
    {
        $item = new class() extends Item{
            protected function getSubjectForExtension(): string
            {
                return 'extas.tmp';
            }
        };

        return $item->$name();
    }
}
