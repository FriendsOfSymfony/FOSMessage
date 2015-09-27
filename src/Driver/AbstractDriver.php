<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Driver;

use FOS\Message\Model\ConversationInterface;
use FOS\Message\Model\MessageInterface;
use FOS\Message\Model\PersonInterface;

/**
 * Abstract driver providing model class customization.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
abstract class AbstractDriver implements DriverInterface
{
    /**
     * @var string
     */
    private $conversationClass;

    /**
     * @var string
     */
    private $conversationPersonClass;

    /**
     * @var string
     */
    private $messageClass;

    /**
     * @var string
     */
    private $messagePersonClass;

    /**
     * Constructor.
     *
     * @param string $conversationClass
     * @param string $conversationPersonClass
     * @param string $messageClass
     * @param string $messagePersonClass
     */
    public function __construct($conversationClass, $conversationPersonClass, $messageClass, $messagePersonClass)
    {
        $this->conversationClass = $conversationClass;
        $this->conversationPersonClass = $conversationPersonClass;
        $this->messageClass = $messageClass;
        $this->messagePersonClass = $messagePersonClass;
    }

    /**
     * @return string
     */
    public function getConversationClass()
    {
        return $this->conversationClass;
    }

    /**
     * @return string
     */
    public function getConversationPersonClass()
    {
        return $this->conversationPersonClass;
    }

    /**
     * @return string
     */
    public function getMessageClass()
    {
        return $this->messageClass;
    }

    /**
     * @return string
     */
    public function getMessagePersonClass()
    {
        return $this->messagePersonClass;
    }

    /**
     * {@inheritdoc}
     */
    public function createConversationModel()
    {
        $class = $this->conversationClass;

        return new $class();
    }

    /**
     * {@inheritdoc}
     */
    public function createConversationPersonModel(ConversationInterface $conversation, PersonInterface $person)
    {
        $class = $this->conversationPersonClass;

        return new $class($conversation, $person);
    }

    /**
     * {@inheritdoc}
     */
    public function createMessageModel(ConversationInterface $conversation, PersonInterface $sender, $body)
    {
        $class = $this->messageClass;

        return new $class($conversation, $sender, $body);
    }

    /**
     * {@inheritdoc}
     */
    public function createMessagePersonModel(MessageInterface $message, PersonInterface $person)
    {
        $class = $this->messagePersonClass;

        return new $class($message, $person);
    }
}
