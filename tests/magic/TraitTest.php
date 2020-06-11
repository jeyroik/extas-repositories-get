<?php
namespace tests\magic;

use extas\components\extensions\ExtensionRepository;
use extas\components\repositories\TSnuffRepository;
use extas\components\THasMagicClass;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

/**
 * Class TraitTest
 *
 * @package tests\magic
 * @author jeyroik <jeyroik@gmail.com>
 */
class TraitTest extends TestCase
{
    use TSnuffRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();

        $this->registerSnuffRepos([
            'extensionRepository' => ExtensionRepository::class,
            'testRepository' => SomeRepository::class
        ]);
    }

    public function tearDown(): void
    {
        $this->unregisterSnuffRepos();
    }

    public function testGetMagicClass()
    {
        $item = new class {
            use THasMagicClass;

            public function getRepo()
            {
                return $this->getMagicClass('testRepository');
            }
        };

        $this->assertInstanceOf(SomeRepository::class, $item->getRepo());
    }
}
