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

use FOS\Message\Event\MessageEvent;
use Webmozart\Assert\Assert;

/**
 * Native event dispatcher usable without any dependency.
 *
 * This dispatcher simply associates a list of listeners to the only event dispatched by the library.
 * Your listeners should use the event payload object to check the event type.
 *
 * For instance:
 *
 * ``` php
 * $dispatcher = new NativeEventDispatcher();
 *
 * $dispatcher->addListener(function($eventName, MessageEvent $event) {
 *      // You can check $eventName to execute code at some specific events.
 *      // $event can be an instance of MessageEvent or an instance of ConversationEvent
 *      // (which extends MessageEvent).
 * });
 *
 * $sender = new Sender($driver, $dispatcher);
 * ```
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class NativeEventDispatcher implements EventDispatcherInterface
{
    /**
     * @var callable[]
     */
    private $listeners;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->listeners = [];
    }

    /**
     * @param callable $listener
     */
    public function addListener($listener)
    {
        Assert::isCallable(
            $listener,
            '$listener expected a callable in NativeEventDispatcher::addListener(). Got: %s'
        );

        $this->listeners[] = $listener;
    }

    /**
     * @param callable $listener
     */
    public function removeListener($listener)
    {
        if ($key = array_search($listener, $this->listeners, true)) {
            unset($this->listeners[$key]);
            $this->listeners = array_values($this->listeners);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($eventName, MessageEvent $event)
    {
        foreach ($this->listeners as $listener) {
            $event = call_user_func_array($listener, [$eventName, $event]);
        }

        return $event;
    }
}
