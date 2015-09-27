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

use FOS\Message\Driver\DriverInterface;
use FOS\Message\Model\ConversationInterface;
use FOS\Message\Model\PersonInterface;

/**
 * Fetch conversations, messages and persons in various ways.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Repository implements RepositoryInterface
{
    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * {@inheritdoc}
     */
    public function getPersonConversations(PersonInterface $person, $tag = null)
    {
        return $this->driver->findPersonConversations($person, $tag);
    }

    /**
     * {@inheritdoc}
     */
    public function getConversation($id)
    {
        return $this->driver->findConversation($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getConversationPerson(ConversationInterface $conversation, PersonInterface $person)
    {
        return $this->driver->findConversationPerson($conversation, $person);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages(ConversationInterface $conversation, $offset = 0, $limit = 20, $sortDirection = 'ASC')
    {
        return $this->driver->findMessages($conversation, $offset, $limit, $sortDirection);
    }
}
