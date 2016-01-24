<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Tests\EventDispatcher;

use FOS\Message\Event\ConversationEvent;
use FOS\Message\Event\MessageEvent;
use FOS\Message\EventDispatcher\EventDispatcherInterface;
use FOS\Message\EventDispatcher\NativeEventDispatcher;
use FOS\Message\Model\Conversation;
use FOS\Message\Model\Message;
use Mockery;
use PHPUnit_Framework_TestCase;

class NativeEventDispatcherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var NativeEventDispatcher
     */
    private $dispatcher;

    public function setUp()
    {
        $this->dispatcher = new NativeEventDispatcher();
    }

    /**
     * @dataProvider getCallbacks
     */
    public function testMessageListener($eventName, $listener)
    {
        $this->dispatcher->addListener($listener);

        $person = Mockery::mock('FOS\Message\Model\PersonInterface');
        $message = new Message(new Conversation(), $person, 'BodyUnchanged');

        $this->assertEquals('BodyUnchanged', $message->getBody());

        $event = $this->dispatcher->dispatch($eventName, new MessageEvent($message));

        $this->assertEquals('BodyEdited', $message->getBody());
        $this->assertEquals('BodyEdited', $event->getMessage()->getBody());
    }

    /**
     * @dataProvider getCallbacks
     */
    public function testConversationListener($eventName, $listener)
    {
        $this->dispatcher->addListener($listener);

        $conversation = new Conversation();
        $conversation->setSubject('SubjectUnchanged');

        $person = Mockery::mock('FOS\Message\Model\PersonInterface');
        $message = new Message($conversation, $person, 'BodyUnchanged');

        $this->assertEquals('SubjectUnchanged', $conversation->getSubject());
        $this->assertEquals('BodyUnchanged', $message->getBody());

        $event = $this->dispatcher->dispatch($eventName, new ConversationEvent($conversation, $message));

        $this->assertEquals('SubjectEdited', $conversation->getSubject());
        $this->assertEquals('SubjectEdited', $event->getConversation()->getSubject());
        $this->assertEquals('BodyEdited', $message->getBody());
        $this->assertEquals('BodyEdited', $event->getMessage()->getBody());
    }

    public function getCallbacks()
    {
        return [
            'Array callable PRE_PERSIST' => [
                EventDispatcherInterface::PRE_PERSIST,
                [$this, 'callbackListener'],
            ],
            'Array callable POST_PERSIST' => [
                EventDispatcherInterface::POST_PERSIST,
                [$this, 'callbackListener'],
            ],
            'Anonymous function PRE_PERSIST' => [
                EventDispatcherInterface::PRE_PERSIST,
                function ($eventName, MessageEvent $event) {
                    if ($event instanceof ConversationEvent) {
                        $event->getConversation()->setSubject('SubjectEdited');
                    }

                    $event->getMessage()->setBody('BodyEdited');

                    return $event;
                },
            ],
            'Anonymous function POST_PERSIST' => [
                EventDispatcherInterface::POST_PERSIST,
                function ($eventName, MessageEvent $event) {
                    if ($event instanceof ConversationEvent) {
                        $event->getConversation()->setSubject('SubjectEdited');
                    }

                    $event->getMessage()->setBody('BodyEdited');

                    return $event;
                },
            ],
        ];
    }

    public function callbackListener($eventName, MessageEvent $event)
    {
        if ($event instanceof ConversationEvent) {
            $event->getConversation()->setSubject('SubjectEdited');
        }

        $event->getMessage()->setBody('BodyEdited');

        return $event;
    }

    public function testMultipleListeners()
    {
        $this->dispatcher->addListener(function ($eventName, MessageEvent $event) {
            $event->getMessage()->setBody('BodyEditedFirst');

            return $event;
        });

        $this->dispatcher->addListener(function ($eventName, MessageEvent $event) {
            $event->getMessage()->setBody('BodyEditedSecond');

            return $event;
        });

        $person = Mockery::mock('FOS\Message\Model\PersonInterface');
        $message = new Message(new Conversation(), $person, 'BodyUnchanged');

        $this->assertEquals('BodyUnchanged', $message->getBody());

        $event = $this->dispatcher->dispatch(EventDispatcherInterface::PRE_PERSIST, new MessageEvent($message));

        $this->assertEquals('BodyEditedSecond', $message->getBody());
        $this->assertEquals('BodyEditedSecond', $event->getMessage()->getBody());
    }

    public function testRemoveListener()
    {
        $this->dispatcher->addListener(function ($eventName, MessageEvent $event) {
            $event->getMessage()->setBody('BodyEditedFirst');

            return $event;
        });

        $secondListener = function ($eventName, MessageEvent $event) {
            $event->getMessage()->setBody('BodyEditedSecond');

            return $event;
        };

        $this->dispatcher->addListener($secondListener);
        $this->dispatcher->removeListener($secondListener);

        $person = Mockery::mock('FOS\Message\Model\PersonInterface');
        $message = new Message(new Conversation(), $person, 'BodyUnchanged');

        $this->assertEquals('BodyUnchanged', $message->getBody());

        $event = $this->dispatcher->dispatch(EventDispatcherInterface::PRE_PERSIST, new MessageEvent($message));

        $this->assertEquals('BodyEditedFirst', $message->getBody());
        $this->assertEquals('BodyEditedFirst', $event->getMessage()->getBody());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddInvalidListener()
    {
        $this->dispatcher->addListener(new \stdClass());
    }
}
