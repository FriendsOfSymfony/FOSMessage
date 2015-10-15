<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Tests;

use FOS\Message\Driver\DriverInterface;
use FOS\Message\Repository;
use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;

class RepositoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DriverInterface|MockInterface
     */
    private $driver;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->driver = Mockery::mock('FOS\Message\Driver\DriverInterface');
        $this->repository = new Repository($this->driver);
    }

    public function testGetPersonConversations()
    {
        $user = Mockery::mock('FOS\Message\Model\PersonInterface');
        $tag = Mockery::mock('FOS\Message\Model\TagInterface');
        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');

        $this->driver->shouldReceive('findPersonConversations')
            ->once()
            ->with($user, $tag)
            ->andReturn([$conversation]);

        $this->assertSame([$conversation], $this->repository->getPersonConversations($user, $tag));
    }

    public function testGetConversation()
    {
        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');

        $this->driver->shouldReceive('findConversation')
            ->once()
            ->with(4)
            ->andReturn($conversation);

        $this->assertSame($conversation, $this->repository->getConversation(4));
    }

    public function testGetConversationPersons()
    {
        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');
        $user = Mockery::mock('FOS\Message\Model\PersonInterface');
        $conversationPerson = Mockery::mock('FOS\Message\Model\ConversationPersonInterface');

        $this->driver->shouldReceive('findConversationPerson')
            ->once()
            ->with($conversation, $user)
            ->andReturn($conversationPerson);

        $this->assertSame($conversationPerson, $this->repository->getConversationPerson($conversation, $user));
    }

    public function testGetMessages()
    {
        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');
        $message = Mockery::mock('FOS\Message\Model\PersonInterface');

        $this->driver->shouldReceive('findMessages')
            ->once()
            ->with($conversation, 5, 10, 'DESC')
            ->andReturn([$message]);

        $this->assertSame([$message], $this->repository->getMessages($conversation, 5, 10, 'DESC'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetMessagesInvalidOffset()
    {
        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');
        $this->repository->getMessages($conversation, 'invalid', 10, 'DESC');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetMessagesInvalidLimit()
    {
        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');
        $this->repository->getMessages($conversation, 0, 'invalid', 'DESC');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetMessagesInvalidSortDirection()
    {
        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');
        $this->repository->getMessages($conversation, 0, 10, 'invalid');
    }
}
