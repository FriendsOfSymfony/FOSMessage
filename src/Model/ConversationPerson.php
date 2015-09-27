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
 * A link between a conversation and a person.
 * This link is taggable: it accepts tags as a way to describe the state
 * of the relation between the person and the conversation of this link.
 * For instance, tags could be "deleted", "archived" or "inbox".
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @author Christian Riesen <chris.riesen@gmail.com>
 */
class ConversationPerson implements ConversationPersonInterface
{
    /**
     * @var ConversationInterface
     */
    protected $conversation;

    /**
     * @var PersonInterface
     */
    protected $person;

    /**
     * @var TagInterface[]|\Doctrine\Common\Collections\Collection
     */
    protected $tags;

    /**
     * Constructor.
     *
     * @param ConversationInterface $conversation
     * @param PersonInterface       $person
     */
    public function __construct(ConversationInterface $conversation, PersonInterface $person)
    {
        $this->conversation = $conversation;
        $this->person = $person;
        $this->tags = new ArrayCollection();
    }

    /**
     * @param ConversationInterface $conversation
     */
    public function setConversation(ConversationInterface $conversation)
    {
        $this->conversation = $conversation;
    }

    /**
     * @param PersonInterface $person
     */
    public function setPerson(PersonInterface $person)
    {
        $this->person = $person;
    }

    /**
     * {@inheritdoc}
     */
    public function getConversation()
    {
        return $this->conversation;
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
    public function addTag(TagInterface $tag)
    {
        if ($this->tags->contains($tag)) {
            return;
        }

        $this->tags->add($tag);
    }

    /**
     * {@inheritdoc}
     */
    public function removeTag(TagInterface $tag)
    {
        return $this->tags->removeElement($tag);
    }

    /**
     * {@inheritdoc}
     */
    public function hasTag(TagInterface $tag)
    {
        return $this->tags->contains($tag);
    }
}
