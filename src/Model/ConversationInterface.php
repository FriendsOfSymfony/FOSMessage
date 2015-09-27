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
 * A conversation is an ordered group of messages with a subject.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @author Christian Riesen <chris.riesen@gmail.com>
 */
interface ConversationInterface
{
    /**
     * Return a unique identifier for this conversation.
     *
     * @return mixed Unique identifier, can vary depending on system used
     */
    public function getId();

    /**
     * Return this conversation subject.
     *
     * @return string
     */
    public function getSubject();

    /**
     * Set this conversation subject.
     *
     * @param string $subject
     *
     * @return void
     */
    public function setSubject($subject);

    /**
     * Add a person to this conversation.
     *
     * @param ConversationPersonInterface $conversationPerson
     */
    public function addConversationPerson(ConversationPersonInterface $conversationPerson);

    /**
     * Remove a person from this conversation.
     *
     * @param ConversationPersonInterface $conversationPerson
     */
    public function removeConversationPerson(ConversationPersonInterface $conversationPerson);

    /**
     * Get all the persons in this conversation.
     *
     * @return ConversationPersonInterface[]|\Doctrine\Common\Collections\Collection
     */
    public function getConversationPersons();

    /**
     * Return whether the given person is in the conversation or not.
     *
     * @param PersonInterface $person
     *
     * @return bool
     */
    public function isPersonInConversation(PersonInterface $person);
}
