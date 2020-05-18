<?php
namespace extas\components\options;

use extas\components\extensions\Extension;
use extas\components\extensions\ExtensionRepositoryGet;
use extas\components\packages\installers\InstallerStageItem;
use extas\components\packages\PackageClass;
use extas\components\plugins\PluginInstallPackageClasses;
use extas\components\SystemContainer;
use extas\interfaces\extensions\IExtension;
use extas\interfaces\extensions\IExtensionRepository;
use extas\interfaces\extensions\IExtensionRepositoryGet;
use extas\interfaces\packages\IPackageClass;
use extas\interfaces\repositories\IRepository;

/**
 * Class RepositoryGetOption
 *
 * @package extas\components\options
 * @author jeyroik <jeyroik@gmail.com>
 */
class RepositoryGetOption extends InstallerStageItem
{
    /**
     * @return bool
     */
    public function __invoke(): bool
    {
        $plugin = $this->getPlugin();
        if (!$plugin instanceof PluginInstallPackageClasses) {
            return false;
        }

        $item = $this->getItem();
        $interface = $item[IPackageClass::FIELD__INTERFACE_NAME];
        $class = $item[IPackageClass::FIELD__CLASS_NAME];
        $alias = lcfirst(basename(str_replace('\\', '/', $class)));

        $this->createAlias($alias, $class);

        /**
         * @var IRepository $extRepo
         * @var IExtension $ext
         */
        $extRepo = SystemContainer::getItem(IExtensionRepository::class);
        $ext = $extRepo->one([IExtension::FIELD__CLASS => ExtensionRepositoryGet::class]);
        $ext
            ? $this->updateExtension($extRepo, $ext, $interface, $alias)
            : $this->createExtension($extRepo, $interface, $alias);

        return false;
    }

    /**
     * @param IRepository $extRepo
     * @param IExtension $ext
     * @param string $interface
     * @param string $alias
     */
    protected function updateExtension(IRepository $extRepo, IExtension $ext, string $interface, string $alias): void
    {
        $methods = $ext->getMethods();
        $newMethods = array_diff([$interface, $alias], $methods);
        if (!empty($newMethods)) {
            $methods = array_merge($methods, $newMethods);
            $ext->setMethods($methods);
            $extRepo->update($ext);
            $this->getOutput()->writeln([
                '[ UPDATE ][ Extension get ] New methods added: "' . implode('", "', [$interface, $alias]) . '"'
            ]);
        }
    }

    /**
     * @param IRepository $extRepo
     * @param string $interface
     * @param string $alias
     */
    protected function createExtension(IRepository $extRepo, string $interface, string $alias): void
    {
        $ext = new Extension([
            Extension::FIELD__CLASS => ExtensionRepositoryGet::class,
            Extension::FIELD__INTERFACE => IExtensionRepositoryGet::class,
            Extension::FIELD__SUBJECT => '*',
            Extension::FIELD__METHODS => [
                $interface, $alias
            ]
        ]);
        $extRepo->create($ext);
        $this->getOutput()->writeln([
            '[ CREATE ][ Extension get ] Created get-extension with methods "'
            . implode('", "', [$interface, $alias]) . '"'
        ]);
    }

    /**
     * @param string $alias
     * @param string $class
     */
    protected function createAlias(string $alias, string $class): void
    {
        $plugin = $this->getPlugin();
        $pcRepo = SystemContainer::getItem($plugin->getPluginRepositoryInterface());
        $aliasExisted = $pcRepo->one([IPackageClass::FIELD__INTERFACE_NAME => $alias]);

        if (!$aliasExisted) {
            $pcRepo->create(new PackageClass([
                PackageClass::FIELD__INTERFACE_NAME => $alias,
                PackageClass::FIELD__CLASS_NAME => $class
            ]));
            $this->getOutput()->writeln([
                '[ CREATE ][ Extension get ] Created alias "' . $alias . '" -> ' . $class
            ]);
        }
    }

    protected function getSubjectForExtension(): string
    {
        return 'repository.get.option';
    }
}
