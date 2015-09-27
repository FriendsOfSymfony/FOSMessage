<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Event;

use FOS\Message\Model\ConversationInterface;
use FOS\Message\Model\MessageInterface;

/**
 * Payload dispatched by the EventDispatcher when a conversation is started.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class ConversationEvent extends MessageEvent
{
    /**
     * @var ConversationInterface
     */
    private $conversation;

    /**
     * @param ConversationInterface $conversation
     * @param MessageInterface      $message
     */
    public function __construct(ConversationInterface $conversation, MessageInterface $message)
    {
        parent::__construct($message);

        $this->conversation = $conversation;
    }

    /**
     * @return ConversationInterface
     */
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * @param ConversationInterface $conversation
     */
    public function setConversation(ConversationInterface $conversation)
    {
        $this->conversation = $conversation;
    }
}
