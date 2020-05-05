<?php
namespace tests;

use extas\components\extensions\Extension;
use extas\components\extensions\ExtensionRepository;
use extas\components\extensions\ExtensionRepositoryGet;
use extas\components\Item;
use extas\components\SystemContainer;
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
    protected IRepository $extRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $env = \Dotenv\Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->extRepo = new ExtensionRepository();
    }

    public function tearDown(): void
    {
        $this->extRepo->delete([Extension::FIELD__CLASS => ExtensionRepositoryGet::class]);
    }

    public function testGetRepository()
    {
        $this->extRepo->create(new Extension([
            Extension::FIELD__CLASS => ExtensionRepositoryGet::class,
            Extension::FIELD__INTERFACE => IExtensionRepositoryGet::class,
            Extension::FIELD__SUBJECT => '*',
            Extension::FIELD__METHODS => ['testRepository']
        ]));
        SystemContainer::addItem('testRepository', SomeRepository::class);
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
