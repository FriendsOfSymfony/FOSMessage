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
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

/**
 * Event dispatcher to send events to the Symfony event dispatcher.
 *
 * Simply provides the Symfony dispatcher and this bridge will dispatch
 * into it the event with name the value of EventDispatcherInterface::EVENT_NAME.
 *
 * You can use the bundle (FOSMessageBundle) if you want to integrate the library in Symfony.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class SymfonyBridgeEventDispatcher implements EventDispatcherInterface
{
    /**
     * @var SymfonyEventDispatcherInterface
     */
    private $symfonyDispatcher;

    /**
     * Cosntructor.
     *
     * @param SymfonyEventDispatcherInterface $symfonyDispatcher
     */
    public function __construct(SymfonyEventDispatcherInterface $symfonyDispatcher)
    {
        $this->symfonyDispatcher = $symfonyDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(MessageEvent $event)
    {
        return $this->symfonyDispatcher->dispatch(EventDispatcherInterface::EVENT_NAME, $event);
    }
}
