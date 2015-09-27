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

use FOS\Message\Model\MessageInterface;

/**
 * Payload dispatched by the EventDispatcher when an answer is sent in a conversation.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class MessageEvent
{
    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param MessageInterface $message
     */
    public function setMessage(MessageInterface $message)
    {
        $this->message = $message;
    }
}
