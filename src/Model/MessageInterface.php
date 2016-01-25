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
 * A single message.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @author Christian Riesen <chris.riesen@gmail.com>
 */
interface MessageInterface
{
    /**
     * Return a unique identifier for this message.
     *
     * @return mixed Unique identifier, can vary depending on system used
     */
    public function getId();

    /**
     * Return this message conversation.
     *
     * @return ConversationInterface Conversation this message belongs to
     */
    public function getConversation();

    /**
     * Return this message sender.
     *
     * @return PersonInterface
     */
    public function getSender();

    /**
     * Return this message body.
     *
     * @return string Body of message
     */
    public function getBody();

    /**
     * Set the body of this message.
     *
     * @param string $body
     */
    public function setBody($body);

    /**
     * Return this message date.
     *
     * @return \DateTime
     */
    public function getDate();

    /**
     * Set the date of this message.
     *
     * @param \DateTime $body
     */
    public function setDate(\DateTime $body);

    /**
     * Add a person to this message.
     *
     * @param MessagePersonInterface $messagePerson
     */
    public function addMessagePerson(MessagePersonInterface $messagePerson);

    /**
     * Remove a person from this message.
     *
     * @param MessagePersonInterface $messagePerson
     */
    public function removeMessagePerson(MessagePersonInterface $messagePerson);

    /**
     * Get all the persons in this message.
     *
     * @return MessagePersonInterface[]|\Doctrine\Common\Collections\Collection
     */
    public function getMessagePersons();

    /**
     * Return the read date of this message by the given person
     * or null if the person did not read this message.
     *
     * @param PersonInterface $person
     *
     * @return \DateTime|null
     */
    public function getReadDate(PersonInterface $person);

    /**
     * Return the MessagePerson object associated to the given person
     * or null if the person is not a member of the message conversation.
     *
     * @param PersonInterface $person
     *
     * @return MessagePerson|null
     */
    public function getMessagePerson(PersonInterface $person);
}
