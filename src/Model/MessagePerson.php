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
 */
class MessagePerson implements MessagePersonInterface
{
    /**
     * @var MessageInterface
     */
    protected $message;

    /**
     * @var PersonInterface
     */
    protected $person;

    /**
     * @var \DateTime
     */
    protected $read;

    /**
     * Constructor.
     *
     * @param MessageInterface $message
     * @param PersonInterface  $person
     */
    public function __construct(MessageInterface $message, PersonInterface $person)
    {
        $this->message = $message;
        $this->person = $person;
        $this->read = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * {@inheritdoc}
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * {@inheritdoc}
     */
    public function setRead()
    {
        $this->read = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function isRead()
    {
        return $this->read instanceof \DateTime;
    }
}
