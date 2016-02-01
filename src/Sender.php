<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message;

use FOS\Message\Driver\DriverInterface;
use FOS\Message\Event\ConversationEvent;
use FOS\Message\Event\MessageEvent;
use FOS\Message\EventDispatcher\EventDispatcherInterface;
use FOS\Message\Model\ConversationInterface;
use FOS\Message\Model\ConversationPersonInterface;
use FOS\Message\Model\MessageInterface;
use FOS\Message\Model\PersonInterface;
use Webmozart\Assert\Assert;

/**
 * Start conversations and send replies.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Sender implements SenderInterface
{
    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param DriverInterface               $driver
     * @param EventDispatcherInterface|null $eventDispatcher
     */
    public function __construct(DriverInterface $driver, EventDispatcherInterface $eventDispatcher = null)
    {
        $this->driver = $driver;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function startConversation(PersonInterface $sender, $recipient, $body, $subject = null)
    {
        if (!is_array($recipient) && !$recipient instanceof \Traversable) {
            $recipient = [$recipient];
        }

        Assert::allIsInstanceOf(
            $recipient,
            'FOS\Message\Model\PersonInterface',
            '$recipient expected ether an instance or a collection of PersonInterface in Sender::startConversation().'
        );

        Assert::string($body, '$body expected a string in Sender::startConversation(). Got: %s');
        Assert::nullOrString($subject, '$subject expected either a string or null in Sender::startConversation(). Got: %s');

        // Create conversation and message
        $conversation = $this->createAndPersistConversation($subject);
        $message = $this->createAndPersistMessage($conversation, $sender, $body);

        // Add the recipients links
        foreach ($recipient as $person) {
            $this->createAndPersistConversationPerson($conversation, $person);
            $this->createAndPersistMessagePerson($message, $person, false);
        }

        // Add the sender link
        $this->createAndPersistConversationPerson($conversation, $sender);
        $this->createAndPersistMessagePerson($message, $sender, true);

        // Dispatch PRE_PERSIST event
        $event = new ConversationEvent($conversation, $message);

        $this->dispatchEvent(
            EventDispatcherInterface::START_CONVERSATION_PRE_PERSIST,
            $event
        );

        $conversation = $event->getConversation();
        $message = $event->getMessage();

        // Persist
        $this->driver->persistConversation($conversation);
        $this->driver->persistMessage($message);
        $this->driver->flush();

        // Dispatch POST_PERSIST event
        $this->dispatchEvent(
            EventDispatcherInterface::START_CONVERSATION_POST_PERSIST,
            new ConversationEvent($conversation, $message)
        );

        return $conversation;
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage(ConversationInterface $conversation, PersonInterface $sender, $body)
    {
        Assert::string($body, '$body expected a string in Sender::sendMessage(). Got: %s');

        // Create the message
        $message = $this->createAndPersistMessage($conversation, $sender, $body);

        // Add the conversation persons links
        foreach ($conversation->getConversationPersons() as $conversationPerson) {
            $person = $conversationPerson->getPerson();
            $this->createAndPersistMessagePerson($message, $person, $person->getId() === $sender->getId());
        }

        // Dispatch PRE_PERSIST event
        $event = new MessageEvent($message);

        $this->dispatchEvent(
            EventDispatcherInterface::SEND_MESSAGE_PRE_PERSIST,
            $event
        );

        $message = $event->getMessage();

        // Persist
        $this->driver->persistMessage($message);
        $this->driver->flush();

        // Dispatch POST_PERSIST event
        $this->dispatchEvent(
            EventDispatcherInterface::SEND_MESSAGE_POST_PERSIST,
            new MessageEvent($message)
        );

        return $message;
    }

    /**
     * Create and persist a conversation object.
     *
     * @param string $subject
     *
     * @return ConversationInterface
     */
    private function createAndPersistConversation($subject)
    {
        $conversation = $this->driver->createConversationModel();
        $conversation->setSubject($subject);

        $this->driver->persistConversation($conversation);

        return $conversation;
    }

    /**
     * Create and persist a conversation person object.
     *
     * @param ConversationInterface $conversation
     * @param PersonInterface       $person
     *
     * @return ConversationPersonInterface
     */
    private function createAndPersistConversationPerson(ConversationInterface $conversation, PersonInterface $person)
    {
        $conversationPerson = $this->driver->createConversationPersonModel($conversation, $person);

        $this->driver->persistConversationPerson($conversationPerson);

        return $conversationPerson;
    }

    /**
     * Create and persist a message object.
     *
     * @param ConversationInterface $conversation
     * @param PersonInterface       $sender
     * @param string                $body
     *
     * @return MessageInterface
     */
    private function createAndPersistMessage(ConversationInterface $conversation, PersonInterface $sender, $body)
    {
        $message = $this->driver->createMessageModel($conversation, $sender, $body);

        $this->driver->persistMessage($message);

        return $message;
    }

    /**
     * Create and persist a message person object.
     *
     * @param MessageInterface $message
     * @param PersonInterface  $person
     * @param bool             $setRead
     *
     * @return ConversationPersonInterface
     */
    private function createAndPersistMessagePerson(MessageInterface $message, PersonInterface $person, $setRead)
    {
        $messagePerson = $this->driver->createMessagePersonModel($message, $person);

        if ($setRead) {
            $messagePerson->setRead();
        }

        $this->driver->persistMessagePerson($messagePerson);

        return $messagePerson;
    }

    /**
     * @param string       $eventName
     * @param MessageEvent $event
     *
     * @return MessageEvent
     */
    private function dispatchEvent($eventName, MessageEvent $event)
    {
        if ($this->eventDispatcher) {
            return $this->eventDispatcher->dispatch($eventName, $event);
        }

        return $event;
    }
}
