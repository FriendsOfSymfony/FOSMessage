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
     * Event name for dispatchers needing an event name.
     * The native dispatcher does not need one so it simply does not use this constant.
     */
    const EVENT_NAME = 'fos_message';

    /**
     * Dispatch an event and return the result.
     *
     * A ConversationEvent is dispatched when a conversation is started.
     * A MessageEvent is dispatched when a message is sent in a conversation.
     *
     * @param MessageEvent|ConversationEvent $event
     *
     * @return MessageEvent
     */
    public function dispatch(MessageEvent $event);
}
