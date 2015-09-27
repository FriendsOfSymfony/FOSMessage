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

use FOS\Message\Model\ConversationInterface;
use FOS\Message\Model\MessageInterface;
use FOS\Message\Model\PersonInterface;

/**
 * Start conversations and send replies.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @author Christian Riesen <chris.riesen@gmail.com>
 */
interface SenderInterface
{
    /**
     * @param PersonInterface       $sender
     * @param array|PersonInterface $recipient One or multiple recepients
     * @param string                $body
     * @param string|null           $subject
     *
     * @return ConversationInterface
     */
    public function startConversation(PersonInterface $sender, $recipient, $body, $subject = null);

    /**
     * @param ConversationInterface $conversation
     * @param PersonInterface       $sender
     * @param string                $body
     *
     * @return MessageInterface
     */
    public function sendMessage(ConversationInterface $conversation, PersonInterface $sender, $body);
}
