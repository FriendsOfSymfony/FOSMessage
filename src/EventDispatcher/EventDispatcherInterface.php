<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\EventDispatcher;

use FOS\Message\Event\ConversationEvent;
use FOS\Message\Event\MessageEvent;

/**
 * EventDispatcher to notify changes to the external tools.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
interface EventDispatcherInterface
{
    /**
     * START_CONVERSATION_PRE_PERSIST is dispatched right before entities
     * are persisted and flushed in the Sender::startConversation() method.
     */
    const START_CONVERSATION_PRE_PERSIST = 'fos_message.start_conversation.pre_persist';

    /**
     * START_CONVERSATION_POST_PERSIST is dispatched right after entities
     * are persisted and flushed in the Sender::startConversation() method.
     */
    const START_CONVERSATION_POST_PERSIST = 'fos_message.start_conversation.post_persist';

    /**
     * SEND_MESSAGE_PRE_PERSIST is dispatched right before entities
     * are persisted and flushed in the Sender::sendMessage() method.
     */
    const SEND_MESSAGE_PRE_PERSIST = 'fos_message.send_message.pre_persist';

    /**
     * SEND_MESSAGE_POST_PERSIST is dispatched right after entities
     * are persisted and flushed in the Sender::sendMessage() method.
     */
    const SEND_MESSAGE_POST_PERSIST = 'fos_message.send_message.post_persist';

    /**
     * Dispatch an event and return the result.
     *
     * A ConversationEvent is dispatched when a conversation is started.
     * A MessageEvent is dispatched when a message is sent in a conversation.
     *
     * See the EventDis dispatched by FOSMessage.
     *
     * @param string                         $eventName
     * @param MessageEvent|ConversationEvent $event
     *
     * @return MessageEvent
     */
    public function dispatch($eventName, MessageEvent $event);
}
