<?php
namespace tests;

use Dotenv\Dotenv;
use extas\components\extensions\Extension;
use extas\components\extensions\ExtensionRepository;
use extas\components\extensions\ExtensionRepositoryGet;
use extas\components\Item;
use extas\components\repositories\TSnuffRepository;
use extas\interfaces\extensions\IExtensionRepositoryGet;
use extas\interfaces\repositories\IRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class ExtensionTest
 *
 * @package tests
 * @author jeyroik@gmail.com
 */
class ExtensionTest extends TestCase
{
    use TSnuffRepository;

    protected IRepository $extRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->extRepo = new ExtensionRepository();
        $this->registerSnuffRepos([
            'extensionRepository' => ExtensionRepository::class,
            'testRepository' => SomeRepository::class
        ]);
    }

    public function tearDown(): void
    {
        $this->unregisterSnuffRepos();
    }

    public function testGetRepository()
    {
        $this->createWithSnuffRepo('extensionRepository', new Extension([
            Extension::FIELD__CLASS => ExtensionRepositoryGet::class,
            Extension::FIELD__INTERFACE => IExtensionRepositoryGet::class,
            Extension::FIELD__SUBJECT => '*',
            Extension::FIELD__METHODS => ['testRepository']
        ]));

        $extendable = new class extends Item {
            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $repo = $extendable->testRepository();
        $this->assertTrue($repo instanceof SomeRepository);
    }
}
