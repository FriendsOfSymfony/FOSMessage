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
     * PRE_PERSIST is dispatched right before entities are persisted and flushed.
     */
    const PRE_PERSIST = 'fos_message.pre_persist';

    /**
     * POST_PERSIST is dispatched right after entities are persisted and flushed.
     */
    const POST_PERSIST = 'fos_message.post_persist';

    /**
     * Dispatch an event and return the result.
     *
     * A ConversationEvent is dispatched when a conversation is started.
     * A MessageEvent is dispatched when a message is sent in a conversation.
     *
     * See the FOSMessageEvents class for the exhaustive list of the events
     * dispatched by FOSMessage.
     *
     * @param string                         $eventName
     * @param MessageEvent|ConversationEvent $event
     *
     * @return MessageEvent
     */
    public function dispatch($eventName, MessageEvent $event);
}
