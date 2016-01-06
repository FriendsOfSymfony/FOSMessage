<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Tests\Puli;

use DI\ContainerBuilder;
use FOS\Message\Driver\DriverInterface;
use FOS\Message\Repository;
use FOS\Message\RepositoryInterface;
use FOS\Message\Sender;
use FOS\Message\SenderInterface;
use FOS\Message\Tagger;
use FOS\Message\TaggerInterface;
use Mockery;
use PHPUnit_Framework_TestCase;

class PhpDiConfigTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        if (PHP_VERSION_ID < 50500) {
            $this->markTestSkipped(sprintf('PHP-DI does not support this PHP version (%s)', PHP_VERSION));
        }
    }

    public function testRepository()
    {
        $container = $this->createContainer(Mockery::mock('FOS\Message\Driver\DriverInterface'));

        $this->assertInstanceOf('FOS\Message\Repository', $container->get('FOS\Message\RepositoryInterface'));
    }

    public function testSender()
    {
        $container = $this->createContainer(Mockery::mock('FOS\Message\Driver\DriverInterface'));

        $this->assertInstanceOf('FOS\Message\Sender', $container->get('FOS\Message\SenderInterface'));
    }

    public function testTagger()
    {
        $container = $this->createContainer(Mockery::mock('FOS\Message\Driver\DriverInterface'));

        $this->assertInstanceOf('FOS\Message\Tagger', $container->get('FOS\Message\TaggerInterface'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp ~^You need to configure the FOSMessage driver in PHP\-DI to use the library~
     */
    public function testRepositoryWithoutDriverFails()
    {
        $container = $this->createContainer();
        $container->get('FOS\Message\RepositoryInterface');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp ~^You need to configure the FOSMessage driver in PHP\-DI to use the library~
     */
    public function testSenderWithoutDriverFails()
    {
        $container = $this->createContainer();
        $container->get('FOS\Message\SenderInterface');
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp ~^You need to configure the FOSMessage driver in PHP\-DI to use the library~
     */
    public function testTaggerWithoutDriverFails()
    {
        $container = $this->createContainer();
        $container->get('FOS\Message\TaggerInterface');
    }

    private function createContainer(DriverInterface $driver = null)
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__.'/../../res/config/php-di.php');

        if ($driver) {
            $builder->addDefinitions([
                'FOS\Message\Driver\DriverInterface' => $driver,
            ]);
        }

        return $builder->build();
    }
}
