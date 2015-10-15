<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Tests\Driver;

use FOS\Message\Driver\DriverInterface;
use FOS\Message\Model\MessageInterface;
use FOS\Message\Model\PersonInterface;
use PHPUnit_Framework_TestCase;

abstract class AbstractDriverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @return DriverInterface
     */
    abstract protected function createDriver();

    /**
     * @return PersonInterface
     */
    abstract protected function createPerson();

    /**
     * Set up the driver.
     */
    public function setUp()
    {
        $this->driver = $this->createDriver();
    }

    public function testFindConversation()
    {
        $conversation = $this->driver->createConversationModel();
        $conversation->setSubject('Subject');
        $this->driver->persistConversation($conversation);

        $this->driver->flush();

        $fetched = $this->driver->findConversation($conversation->getId());

        $this->assertInstanceOf(get_class($conversation), $fetched);
        $this->assertEquals($conversation->getSubject(), $fetched->getSubject());
    }

    public function testFindPersonConversations()
    {
        $person = $this->createPerson();

        $conversation = $this->driver->createConversationModel();
        $conversation->setSubject('Subject');
        $this->driver->persistConversation($conversation);

        $personModel = $this->driver->createConversationPersonModel($conversation, $person);
        $this->driver->persistConversationPerson($personModel);

        $this->driver->flush();

        $fetched = $this->driver->findPersonConversations($person);

        $this->assertCount(1, $fetched);
        $this->assertInstanceOf(get_class($conversation), $fetched[0]);
        $this->assertEquals($conversation->getSubject(), $fetched[0]->getSubject());
    }

    public function testFindConversationPerson()
    {
        $person = $this->createPerson();

        $conversation = $this->driver->createConversationModel();
        $conversation->setSubject('Subject');
        $this->driver->persistConversation($conversation);

        $personModel = $this->driver->createConversationPersonModel($conversation, $person);
        $this->driver->persistConversationPerson($personModel);

        $this->driver->flush();

        $fetched = $this->driver->findConversationPerson($conversation, $person);

        $this->assertInstanceOf(get_class($personModel), $fetched);
        $this->assertEquals($personModel->getConversation(), $conversation);
        $this->assertEquals($personModel->getPerson(), $person);
    }

    public function testFindMessagesAsc()
    {
        $conversation = $this->driver->createConversationModel();
        $conversation->setSubject('Subject');
        $this->driver->persistConversation($conversation);

        $firstMessage = $this->driver->createMessageModel($conversation, $this->createPerson(), 'Body1');
        $this->driver->persistMessage($firstMessage);

        $secondMessage = $this->driver->createMessageModel($conversation, $this->createPerson(), 'Body2');
        $this->driver->persistMessage($secondMessage);

        $this->driver->flush();

        $fetched = $this->driver->findMessages($conversation, 0, 20, 'ASC');

        $this->assertCount(2, $fetched);
        $this->assertMessagesEquals($firstMessage, $fetched[0]);
        $this->assertMessagesEquals($secondMessage, $fetched[1]);
    }

    public function testFindMessagesDesc()
    {
        $conversation = $this->driver->createConversationModel();
        $conversation->setSubject('Subject');
        $this->driver->persistConversation($conversation);

        $firstMessage = $this->driver->createMessageModel($conversation, $this->createPerson(), 'Body1');
        $this->driver->persistMessage($firstMessage);

        $secondMessage = $this->driver->createMessageModel($conversation, $this->createPerson(), 'Body2');
        $this->driver->persistMessage($secondMessage);

        $this->driver->flush();

        $fetched = $this->driver->findMessages($conversation, 0, 20, 'DESC');

        $this->assertCount(2, $fetched);
        $this->assertMessagesEquals($secondMessage, $fetched[0]);
        $this->assertMessagesEquals($firstMessage, $fetched[1]);
    }

    public function testFindMessagesLimit()
    {
        $conversation = $this->driver->createConversationModel();
        $conversation->setSubject('Subject');
        $this->driver->persistConversation($conversation);

        $firstMessage = $this->driver->createMessageModel($conversation, $this->createPerson(), 'Body1');
        $this->driver->persistMessage($firstMessage);

        $secondMessage = $this->driver->createMessageModel($conversation, $this->createPerson(), 'Body2');
        $this->driver->persistMessage($secondMessage);

        $thirdMessage = $this->driver->createMessageModel($conversation, $this->createPerson(), 'Body3');
        $this->driver->persistMessage($thirdMessage);

        $this->driver->flush();

        $fetched = $this->driver->findMessages($conversation, 0, 2, 'ASC');

        $this->assertCount(2, $fetched);
        $this->assertMessagesEquals($firstMessage, $fetched[0]);
        $this->assertMessagesEquals($secondMessage, $fetched[1]);

        $fetched = $this->driver->findMessages($conversation, 1, 2, 'ASC');

        $this->assertCount(2, $fetched);
        $this->assertMessagesEquals($secondMessage, $fetched[0]);
        $this->assertMessagesEquals($thirdMessage, $fetched[1]);
    }

    /**
     * @param MessageInterface $excepted
     * @param MessageInterface $actual
     */
    private function assertMessagesEquals($excepted, $actual)
    {
        $this->assertInstanceOf(get_class($excepted), $actual);
        $this->assertEquals($excepted->getBody(), $actual->getBody());
        $this->assertEquals($excepted->getConversation()->getId(), $actual->getConversation()->getId());
        $this->assertEquals($excepted->getSender()->getId(), $actual->getSender()->getId());
    }
}
