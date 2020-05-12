<?php
namespace tests;

use extas\components\extensions\ExtensionRepository;
use extas\components\extensions\ExtensionRepositoryGet;
use extas\components\extensions\TSnuffExtensions;
use extas\components\options\RepositoryGetOption;
use extas\components\packages\PackageClass;
use extas\components\packages\PackageClassRepository;
use extas\components\plugins\PluginInstallPackageClasses;
use extas\interfaces\extensions\IExtension;
use extas\interfaces\packages\IPackageClass;
use extas\interfaces\packages\IPackageClassRepository;
use extas\interfaces\repositories\IRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Class RepositoryGetOptionTest
 *
 * @package tests
 * @@author jeyroik <jeyroik@gmail.com>
 */
class RepositoryGetOptionTest extends TestCase
{
    use TSnuffExtensions;

    protected IRepository $extRepo;
    protected IRepository $packageClassesRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->extRepo = new ExtensionRepository();
        $this->packageClassesRepo = new PackageClassRepository();
        $this->addReposForExt([
            IPackageClassRepository::class => PackageClassRepository::class
        ]);
    }

    public function tearDown(): void
    {
        $this->deleteSnuffExtensions();
        $this->packageClassesRepo->delete([IPackageClass::FIELD__CLASS_NAME => 'test\\components\\IsOk']);
    }

    public function testOptionCreateExt()
    {
        $option = $this->getOption();
        $option();

        /**
         * @var IPackageClass $alias
         */
        $alias = $this->packageClassesRepo->one([IPackageClass::FIELD__INTERFACE_NAME => 'isOk']);
        $this->assertNotEmpty($alias);
        $this->assertEquals('test\\components\\IsOk', $alias->getClass());

        /**
         * @var IExtension $ext
         */
        $ext = $this->extRepo->one([IExtension::FIELD__CLASS => ExtensionRepositoryGet::class]);
        $this->assertNotEmpty($ext);
        $this->assertEquals(['test\\interfaces\\IIsOk', 'isOk'], $ext->getMethods());
    }

    public function testOptionUpdateExt()
    {
        $this->createRepoExt(['test', 'isOk']);

        $option = $this->getOption();
        $option();

        /**
         * @var IExtension $ext
         */
        $ext = $this->extRepo->one([IExtension::FIELD__CLASS => ExtensionRepositoryGet::class]);
        $this->assertNotEmpty($ext);
        $this->assertEquals(['test', 'isOk', 'test\\interfaces\\IIsOk'], $ext->getMethods());
    }

    /**
     * @return RepositoryGetOption
     */
    protected function getOption()
    {
        return new RepositoryGetOption([
            RepositoryGetOption::FIELD__ITEM => [
                PackageClass::FIELD__INTERFACE_NAME => 'test\\interfaces\\IIsOk',
                PackageClass::FIELD__CLASS_NAME => 'test\\components\\IsOk'
            ],
            RepositoryGetOption::FIELD__PLUGIN => new PluginInstallPackageClasses(),
            RepositoryGetOption::FIELD__OUTPUT => new NullOutput()
        ]);
    }
}
