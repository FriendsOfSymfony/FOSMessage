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
use FOS\Message\EventDispatcher\EventDispatcherInterface;
use FOS\Message\Sender;
use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;

class SenderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DriverInterface|MockInterface
     */
    private $driver;

    /**
     * @var EventDispatcherInterface|MockInterface
     */
    private $dispatcher;

    /**
     * @var Sender
     */
    private $sender;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->driver = Mockery::mock('FOS\Message\Driver\DriverInterface');
        $this->dispatcher = Mockery::mock('FOS\Message\EventDispatcher\EventDispatcherInterface');
        $this->sender = new Sender($this->driver, $this->dispatcher);
    }

    public function testStartConversation()
    {
        $from = Mockery::mock('FOS\Message\Model\PersonInterface');
        $firstRecipient = Mockery::mock('FOS\Message\Model\PersonInterface');
        $secondRecipient = Mockery::mock('FOS\Message\Model\PersonInterface');

        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');
        $conversation->shouldReceive('setSubject')->with('Subject')->andReturnSelf();

        $message = Mockery::mock('FOS\Message\Model\MessageInterface');

        $fromConversationModel = Mockery::mock('FOS\Message\Model\ConversationPersonInterface');
        $firstConversationModel = Mockery::mock('FOS\Message\Model\ConversationPersonInterface');
        $secondConversationModel = Mockery::mock('FOS\Message\Model\ConversationPersonInterface');

        $fromMessageModel = Mockery::mock('FOS\Message\Model\MessagePersonInterface');
        $fromMessageModel->shouldReceive('setRead')->withNoArgs()->andReturnSelf();

        $firstMessageModel = Mockery::mock('FOS\Message\Model\MessagePersonInterface');
        $secondMessageModel = Mockery::mock('FOS\Message\Model\MessagePersonInterface');

        // Create the conversation
        $this->driver->shouldReceive('createConversationModel')
            ->once()
            ->withNoArgs()
            ->andReturn($conversation);

        $this->driver->shouldReceive('persistConversation')
            ->once()
            ->with($conversation)
            ->andReturn(true);

        // Create the first message
        $this->driver->shouldReceive('createMessageModel')
            ->once()
            ->with($conversation, $from, 'Body')
            ->andReturn($message);

        $this->driver->shouldReceive('persistMessage')
            ->once()
            ->with($message)
            ->andReturn(true);

        // Create links between persons and conversation
        $this->driver->shouldReceive('createConversationPersonModel')
            ->once()
            ->with($conversation, $from)
            ->andReturn($fromConversationModel);

        $this->driver->shouldReceive('persistConversationPerson')
            ->once()
            ->with($fromConversationModel)
            ->andReturn(true);

        $this->driver->shouldReceive('createConversationPersonModel')
            ->once()
            ->with($conversation, $firstRecipient)
            ->andReturn($firstConversationModel);

        $this->driver->shouldReceive('persistConversationPerson')
            ->once()
            ->with($firstConversationModel)
            ->andReturn(true);

        $this->driver->shouldReceive('createConversationPersonModel')
            ->once()
            ->with($conversation, $secondRecipient)
            ->andReturn($secondConversationModel);

        $this->driver->shouldReceive('persistConversationPerson')
            ->once()
            ->with($secondConversationModel)
            ->andReturn(true);

        // Create links between persons and first message
        $this->driver->shouldReceive('createMessagePersonModel')
            ->once()
            ->with($message, $from)
            ->andReturn($fromMessageModel);

        $this->driver->shouldReceive('persistMessagePerson')
            ->once()
            ->with($fromMessageModel)
            ->andReturn(true);

        $this->driver->shouldReceive('createMessagePersonModel')
            ->once()
            ->with($message, $firstRecipient)
            ->andReturn($firstMessageModel);

        $this->driver->shouldReceive('persistMessagePerson')
            ->once()
            ->with($firstMessageModel)
            ->andReturn(true);

        $this->driver->shouldReceive('createMessagePersonModel')
            ->once()
            ->with($message, $secondRecipient)
            ->andReturn($secondMessageModel);

        $this->driver->shouldReceive('persistMessagePerson')
            ->once()
            ->with($secondMessageModel)
            ->andReturn(true);

        // Final flush
        $this->driver->shouldReceive('flush')
            ->once()
            ->withNoArgs()
            ->andReturn(true);

        // Dispatcher
        $this->dispatcher->shouldReceive('dispatch')
            ->with(Mockery::type('FOS\Message\Event\ConversationEvent'))
            ->once()
            ->andReturn(true);

        $this->assertSame(
            $conversation,
            $this->sender->startConversation(
                $from,
                [$firstRecipient, $secondRecipient],
                'Body',
                'Subject'
            )
        );
    }

    public function testSendMessage()
    {
        $from = Mockery::mock('FOS\Message\Model\PersonInterface');
        $from->shouldReceive('getId')->withNoArgs()->once()->andReturn(1);

        $firstRecipient = Mockery::mock('FOS\Message\Model\PersonInterface');
        $firstRecipient->shouldReceive('getId')->withNoArgs()->once()->andReturn(2);

        $secondRecipient = Mockery::mock('FOS\Message\Model\PersonInterface');
        $secondRecipient->shouldReceive('getId')->withNoArgs()->once()->andReturn(2);

        $fromConversationModel = Mockery::mock('FOS\Message\Model\ConversationPersonInterface');
        $fromConversationModel->shouldReceive('getPerson')->withNoArgs()->once()->andReturn($from);

        $firstConversationModel = Mockery::mock('FOS\Message\Model\ConversationPersonInterface');
        $firstConversationModel->shouldReceive('getPerson')->withNoArgs()->once()->andReturn($firstRecipient);

        $secondConversationModel = Mockery::mock('FOS\Message\Model\ConversationPersonInterface');
        $secondConversationModel->shouldReceive('getPerson')->withNoArgs()->once()->andReturn($secondRecipient);

        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');
        $conversation->shouldReceive('getConversationPersons')
            ->withNoArgs()
            ->andReturn([$fromConversationModel, $firstConversationModel, $secondConversationModel]);

        $fromMessageModel = Mockery::mock('FOS\Message\Model\MessagePersonInterface');
        $fromMessageModel->shouldReceive('setRead')->withNoArgs()->andReturnSelf();

        $firstMessageModel = Mockery::mock('FOS\Message\Model\MessagePersonInterface');
        $secondMessageModel = Mockery::mock('FOS\Message\Model\MessagePersonInterface');

        $message = Mockery::mock('FOS\Message\Model\MessageInterface');

        // Create the message
        $this->driver->shouldReceive('createMessageModel')
            ->once()
            ->with($conversation, $from, 'ReplyBody')
            ->andReturn($message);

        $this->driver->shouldReceive('persistMessage')
            ->once()
            ->with($message)
            ->andReturn(true);

        // Create links between persons and message
        $this->driver->shouldReceive('createMessagePersonModel')
            ->once()
            ->with($message, $from)
            ->andReturn($fromMessageModel);

        $this->driver->shouldReceive('persistMessagePerson')
            ->once()
            ->with($fromMessageModel)
            ->andReturn(true);

        $this->driver->shouldReceive('createMessagePersonModel')
            ->once()
            ->with($message, $firstRecipient)
            ->andReturn($firstMessageModel);

        $this->driver->shouldReceive('persistMessagePerson')
            ->once()
            ->with($firstMessageModel)
            ->andReturn(true);

        $this->driver->shouldReceive('createMessagePersonModel')
            ->once()
            ->with($message, $secondRecipient)
            ->andReturn($secondMessageModel);

        $this->driver->shouldReceive('persistMessagePerson')
            ->once()
            ->with($secondMessageModel)
            ->andReturn(true);

        // Final flush
        $this->driver->shouldReceive('flush')
            ->once()
            ->withNoArgs()
            ->andReturn(true);

        // Dispatcher
        $this->dispatcher->shouldReceive('dispatch')
            ->with(Mockery::type('FOS\Message\Event\MessageEvent'))
            ->once()
            ->andReturn(true);

        $this->assertSame(
            $message,
            $this->sender->sendMessage(
                $conversation,
                $from,
                'ReplyBody'
            )
        );
    }
}
