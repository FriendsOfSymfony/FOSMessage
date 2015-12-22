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

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 */
class SymfonyEvent extends Event
{
    /**
     * @var MessageEvent
     */
    private $event;

    /**
     * @param MessageEvent $event
     */
    public function __construct(MessageEvent $event)
    {
        $this->event = $event;
    }

    /**
     * @return MessageEvent
     */
    public function getOriginalEvent()
    {
        return $this->event;
    }
}
