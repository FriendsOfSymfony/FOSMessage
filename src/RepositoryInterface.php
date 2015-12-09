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

use Doctrine\Common\Collections\Collection;
use FOS\Message\Model\ConversationInterface;
use FOS\Message\Model\ConversationPersonInterface;
use FOS\Message\Model\MessageInterface;
use FOS\Message\Model\PersonInterface;
use FOS\Message\Model\TagInterface;
use InvalidArgumentException;

/**
 * Fetch conversations, messages and persons in various ways.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @author Christian Riesen <chris.riesen@gmail.com>
 */
interface RepositoryInterface
{
    /**
     * Get conversations of one person
     * An optionnal tag can be specified to filter the list.
     *
     * @param PersonInterface   $person
     * @param TagInterface|null $tag
     *
     * @return ConversationInterface[]|Collection A collection of conversations.
     */
    public function getPersonConversations(PersonInterface $person, $tag = null);

    /**
     * Get single conversation.
     *
     * @param int $id
     *
     * @return ConversationInterface
     */
    public function getConversation($id);

    /**
     * Get a single ConversationPerson entity corresponding to the link
     * between the given conversation and the given person or null if no
     * such link is found.
     *
     * @param ConversationInterface $conversation
     * @param PersonInterface       $person
     *
     * @return ConversationPersonInterface|null
     */
    public function getConversationPerson(ConversationInterface $conversation, PersonInterface $person);

    /**
     * Get messages of a conversation.
     *
     * @param ConversationInterface $conversation
     * @param int                   $limit
     * @param int                   $offset
     * @param string                $sortDirection
     *
     * @throws InvalidArgumentException If the offset is not an integer.
     * @throws InvalidArgumentException If the limit is not an integer.
     * @throws InvalidArgumentException If the sort direction is neither ASC nor DESC.
     *
     * @return MessageInterface[]|Collection A collection of messages.
     */
    public function getMessages(ConversationInterface $conversation, $offset = 0, $limit = 20, $sortDirection = 'ASC');
}
