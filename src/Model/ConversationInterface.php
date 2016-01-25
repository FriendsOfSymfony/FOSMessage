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

use Doctrine\Common\Collections\Collection;

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
     */
    public function setSubject($subject);

    /**
     * Get all the messages in this conversation.
     * Return a list ordered by date ascending.
     *
     * @return Message[]|Collection
     */
    public function getMessages();

    /**
     * Get the first unread message of the conversation by a person,
     * or null if all the messages have been read.
     *
     * @param PersonInterface $person
     *
     * @return null
     */
    public function getFirstUnreadMessage(PersonInterface $person);

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

    /**
     * Return the ConversationPerson object associated to the given person
     * or null if the person is not a member of this conversation.
     *
     * @param PersonInterface $person
     *
     * @return ConversationPerson|null
     */
    public function getConversationPerson(PersonInterface $person);
}
