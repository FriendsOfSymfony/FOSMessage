<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Model;

/**
 * A link between a message and a person.
 * This link store the state of the message (read / unread)
 * according to the person.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @author Christian Riesen <chris.riesen@gmail.com>
 */
interface MessagePersonInterface
{
    /**
     * Return the message associated to this link.
     *
     * @return MessageInterface Message associated to this link
     */
    public function getMessage();

    /**
     * Return the person associated to this link.
     *
     * @return PersonInterface Person associated to this link
     */
    public function getPerson();

    /**
     * Set the read date of the message by the person as now.
     */
    public function setRead();

    /**
     * Return the exact time the message was read by the person.
     *
     * @return \DateTime
     */
    public function getRead();

    /**
     * Returns if the message was read or not.
     *
     * Not strictly needed, just a helper.
     *
     * @return bool
     */
    public function isRead();
}
