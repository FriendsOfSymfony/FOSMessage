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
use FOS\Message\Model\ConversationPersonInterface;
use FOS\Message\Model\PersonInterface;
use FOS\Message\Model\TagInterface;

/**
 * Add and remove tags associated to conversation by persons.
 * A tag is a simple marker used to filter conversations afterwards
 * (such as "archived", "deleted", etc.).
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Tagger implements TaggerInterface
{
    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @param DriverInterface     $driver
     * @param RepositoryInterface $repository
     */
    public function __construct(DriverInterface $driver, RepositoryInterface $repository)
    {
        $this->driver = $driver;
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function addTag(ConversationInterface $conversation, PersonInterface $person, TagInterface $tag)
    {
        $conversationPerson = $this->repository->getConversationPerson($conversation, $person);

        if (!$conversationPerson instanceof ConversationPersonInterface) {
            throw new \LogicException(sprintf(
                'Link between conversation %s and person %s was not found in %s',
                $conversation->getId(), $person->getId(), __METHOD__
            ));
        }

        if ($conversationPerson->hasTag($tag)) {
            return false;
        }

        $conversationPerson->addTag($tag);

        $this->driver->persistConversationPerson($conversationPerson);
        $this->driver->flush();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTag(ConversationInterface $conversation, PersonInterface $person, TagInterface $tag)
    {
        $conversationPerson = $this->repository->getConversationPerson($conversation, $person);

        if (!$conversationPerson instanceof ConversationPersonInterface) {
            return false;
        }

        return $conversationPerson->hasTag($tag);
    }

    /**
     * {@inheritdoc}
     */
    public function removeTag(ConversationInterface $conversation, PersonInterface $person, TagInterface $tag)
    {
        $conversationPerson = $this->repository->getConversationPerson($conversation, $person);

        if (!$conversationPerson instanceof ConversationPersonInterface) {
            throw new \LogicException(sprintf(
                'Link between conversation %s and person %s was not found in %s',
                $conversation->getId(), $person->getId(), __METHOD__
            ));
        }

        if (!$conversationPerson->hasTag($tag)) {
            return false;
        }

        $conversationPerson->removeTag($tag);

        $this->driver->persistConversationPerson($conversationPerson);
        $this->driver->flush();

        return true;
    }
}
