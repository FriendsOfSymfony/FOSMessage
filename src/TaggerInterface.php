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

use FOS\Message\Model\ConversationInterface;
use FOS\Message\Model\PersonInterface;
use FOS\Message\Model\TagInterface;

/**
 * Add and remove tags associated to conversation by persons
 * A tag is a simple marker used to filter conversations afterwards
 * (such as "inbox", "archived", "read", etc.).
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @author Christian Riesen <chris.riesen@gmail.com>
 */
interface TaggerInterface
{
    /**
     * Add a tag on the given conversation for the given person
     * This tag aims to be used as a filter afterwards.
     *
     * @param ConversationInterface $conversation
     * @param PersonInterface       $person
     * @param TagInterface          $tag
     *
     * @return bool Whether the tag has been added successfully or not
     */
    public function addTag(ConversationInterface $conversation, PersonInterface $person, TagInterface $tag);

    /**
     * Check if the given conversation has a tag for the given person.
     *
     * @param ConversationInterface $conversation
     * @param PersonInterface       $person
     * @param TagInterface          $tag
     *
     * @return bool
     */
    public function hasTag(ConversationInterface $conversation, PersonInterface $person, TagInterface $tag);

    /**
     * Remove a tag from the given conversation for the given person.
     *
     * @param ConversationInterface $conversation
     * @param PersonInterface       $person
     * @param TagInterface          $tag
     *
     * @return bool Whether the tag has been removed successfully or not
     */
    public function removeTag(ConversationInterface $conversation, PersonInterface $person, TagInterface $tag);
}
