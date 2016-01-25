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
use Webmozart\Assert\Assert;

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
    public function countMessages(ConversationInterface $conversation)
    {
        return $this->driver->countMessages($conversation);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages(ConversationInterface $conversation, $offset = 0, $limit = 20, $sortDirection = 'ASC')
    {
        Assert::integer($offset, '$offset expected an integer in Repository::getMessages(). Got: %s');
        Assert::integer($limit, '$limit expected an integer in Repository::getMessages(). Got: %s');

        Assert::oneOf(
            strtoupper($sortDirection),
            ['ASC', 'DESC'],
            '$sortDirection expected either ASC or DESC in Repository::getMessages(). Got: %s'
        );

        return $this->driver->findMessages($conversation, $offset, $limit, $sortDirection);
    }
}
