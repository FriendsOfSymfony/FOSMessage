<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Tests\Driver\Doctrine\ORM;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Tools\ResolveTargetEntityListener;
use FOS\Message\Driver\Doctrine\ORM\DoctrineORMDriver;
use FOS\Message\Tests\Driver\AbstractDriverTest;
use FOS\Message\Tests\Driver\Doctrine\ORM\Entity\TestPerson;

class DoctrineORMDriverTest extends AbstractDriverTest
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var int
     */
    private static $createdUsers = 0;

    /**
     * @var string
     */
    private static $dbFile;

    /**
     * Build the SQLite database before the tests suite.
     */
    public static function setUpBeforeClass()
    {
        self::$dbFile = sys_get_temp_dir().'/fos-message/doctrine-orm.db';

        if (file_exists(self::$dbFile)) {
            unlink(self::$dbFile);
        } elseif (!file_exists(dirname(self::$dbFile))) {
            mkdir(dirname(self::$dbFile), 0777, true);
        }

        $em = self::createEntityManager();

        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $tool->updateSchema([
            $em->getClassMetadata('FOS\Message\Driver\Doctrine\ORM\Entity\Conversation'),
            $em->getClassMetadata('FOS\Message\Driver\Doctrine\ORM\Entity\ConversationPerson'),
            $em->getClassMetadata('FOS\Message\Driver\Doctrine\ORM\Entity\Message'),
            $em->getClassMetadata('FOS\Message\Driver\Doctrine\ORM\Entity\MessagePerson'),
            $em->getClassMetadata('FOS\Message\Driver\Doctrine\ORM\Entity\Tag'),
            $em->getClassMetadata('FOS\Message\Tests\Driver\Doctrine\ORM\Entity\TestPerson'),
        ]);
    }

    /**
     * Remove the SQLite database after the tests suite.
     */
    public static function tearDownAfterClass()
    {
        if (file_exists(self::$dbFile)) {
            unlink(self::$dbFile);
        }
    }

    protected function createDriver()
    {
        $this->entityManager = self::createEntityManager();

        return new DoctrineORMDriver($this->entityManager);
    }

    /**
     * @return TestPerson
     */
    protected function createPerson()
    {
        ++self::$createdUsers;

        $user = new TestPerson();
        $user->setLogin('user'.self::$createdUsers);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @return EntityManager
     */
    private static function createEntityManager()
    {
        $config = \Doctrine\ORM\Tools\Setup::createConfiguration(true);

        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver([
            __DIR__.'/../../../../src/Driver/Doctrine/ORM/Entity',
            __DIR__.'/Entity',
        ], false));

        $rtel = new ResolveTargetEntityListener();
        $rtel->addResolveTargetEntity(
            'FOS\Message\Model\PersonInterface',
            'FOS\Message\Tests\Driver\Doctrine\ORM\Entity\TestPerson',
            []
        );

        $evm = new EventManager();
        $evm->addEventListener(Events::loadClassMetadata, $rtel);

        $dbParams = [
            'driver' => 'pdo_sqlite',
            'path'   => self::$dbFile,
        ];

        return \Doctrine\ORM\EntityManager::create($dbParams, $config, $evm);
    }
}
