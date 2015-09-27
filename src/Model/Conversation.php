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

use Doctrine\Common\Collections\ArrayCollection;

/**
 * A conversation is an ordered group of messages with a subject.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class Conversation implements ConversationInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var ConversationPersonInterface[]|\Doctrine\Common\Collections\Collection
     */
    protected $persons;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->persons = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * {@inheritdoc}
     */
    public function addConversationPerson(ConversationPersonInterface $conversationPerson)
    {
        if (!$this->isPersonInConversation($conversationPerson->getPerson())) {
            $this->persons->add($conversationPerson);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeConversationPerson(ConversationPersonInterface $conversationPerson)
    {
        $this->persons->removeElement($conversationPerson);
    }

    /**
     * {@inheritdoc}
     */
    public function getConversationPersons()
    {
        return $this->persons;
    }

    /**
     * {@inheritdoc}
     */
    public function isPersonInConversation(PersonInterface $person)
    {
        foreach ($this->persons as $conversationPerson) {
            if ($conversationPerson->getPerson()->getId() === $person->getId()) {
                return true;
            }
        }

        return false;
    }
}
