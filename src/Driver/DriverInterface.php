<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Driver;

use FOS\Message\Model\ConversationInterface;
use FOS\Message\Model\ConversationPersonInterface;
use FOS\Message\Model\MessageInterface;
use FOS\Message\Model\MessagePersonInterface;
use FOS\Message\Model\PersonInterface;
use FOS\Message\Model\TagInterface;

/**
 * A driver is a bridge between the FOSMessage library and a
 * persistance layer used to save and retreive messages and conversations.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @author Christian Riesen <chris.riesen@gmail.com>
 */
interface DriverInterface
{
    /**
     * Create and return a conversation object.
     *
     * @return ConversationInterface The conversation object.
     */
    public function createConversationModel();

    /**
     * Create a ConversationPerson model object.
     *
     * @param ConversationInterface $conversation
     * @param PersonInterface       $person
     *
     * @return ConversationPersonInterface
     */
    public function createConversationPersonModel(ConversationInterface $conversation, PersonInterface $person);

    /**
     * Create and return a message object.
     *
     * @param ConversationInterface $conversation The conversation the message belongs to.
     * @param PersonInterface       $sender
     * @param string                $body
     *
     * @return MessageInterface
     */
    public function createMessageModel(ConversationInterface $conversation, PersonInterface $sender, $body);

    /**
     * Create a MessagePerson model object.
     *
     * @param MessageInterface $message
     * @param PersonInterface  $person
     *
     * @return MessagePersonInterface
     */
    public function createMessagePersonModel(MessageInterface $message, PersonInterface $person);

    /**
     * Return the list of conversations of one person
     * An optionnal tag can be specified to filter the list.
     *
     * @param PersonInterface   $person
     * @param TagInterface|null $tag
     *
     * @return PersonInterface[]
     */
    public function findPersonConversations(PersonInterface $person, TagInterface $tag = null);

    /**
     * Return a single conversation by its identifier or null if
     * no conversation is found.
     *
     * @param int $id
     *
     * @return ConversationInterface|null
     */
    public function findConversation($id);

    /**
     * Return a single ConversationPerson entity corresponding to the link
     * between the given conversation and the given person or null if no
     * such link is found.
     *
     * @param ConversationInterface $conversation
     * @param PersonInterface       $person
     *
     * @return ConversationPersonInterface|null
     */
    public function findConversationPerson(ConversationInterface $conversation, PersonInterface $person);

    /**
     * Return the ordered list of messages in a conversation.
     *
     * @param ConversationInterface $conversation
     * @param int                   $limit
     * @param int                   $offset
     * @param string                $sortDirection
     *
     * @return MessageInterface[] The messages
     */
    public function findMessages(ConversationInterface $conversation, $offset = 0, $limit = 20, $sortDirection = 'ASC');

    /**
     * Persist a conversation in the persistance layer.
     * The flush method will be called later so this method can rely
     * on it to really write into the persistance layer.
     *
     * @param ConversationInterface $conversation The conversation to persist
     *
     * @return bool True if the save succeed, false otherwise
     */
    public function persistConversation(ConversationInterface $conversation);

    /**
     * Persist a ConversationPerson object in the persistance layer.
     * The flush method will be called later so this method can rely
     * on it to really write into the persistance layer.
     *
     * @param ConversationPersonInterface $conversationPerson The entity to persist
     *
     * @return bool True if the save succeed, false otherwise
     */
    public function persistConversationPerson(ConversationPersonInterface $conversationPerson);

    /**
     * Persist a message in the persistance layer.
     * The flush method will be called later so this method can rely
     * on it to really write into the persistance layer.
     *
     * @param MessageInterface $message The message to persist
     *
     * @return bool True if the save succeed, false otherwise
     */
    public function persistMessage(MessageInterface $message);

    /**
     * Persist a MessagePerson in the persistance layer.
     * The flush method will be called later so this method can rely
     * on it to really write into the persistance layer.
     *
     * @param MessagePersonInterface $messagePerson The entity to persist
     *
     * @return bool True if the save succeed, false otherwise
     */
    public function persistMessagePerson(MessagePersonInterface $messagePerson);

    /**
     * Flush the previous `persistXXX()` calls by really writing in the
     * persistance layer.
     *
     * @return void
     */
    public function flush();
}
