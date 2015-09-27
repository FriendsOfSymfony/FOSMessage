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
 * A link between a conversation and a person.
 * This link is taggable: it accepts tags as a way to describe the state
 * of the relation between the person and the conversation of this link.
 * For instance, tags could be "deleted", "archived" or "inbox".
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @author Christian Riesen <chris.riesen@gmail.com>
 */
interface ConversationPersonInterface
{
    /**
     * Return the conversation associated to this link.
     *
     * @return ConversationInterface Conversation associated to this link
     */
    public function getConversation();

    /**
     * Return the person associated to this link.
     *
     * @return PersonInterface Person associated to this link
     */
    public function getPerson();

    /**
     * Add the given tag to this entity.
     *
     * @param TagInterface $tag
     */
    public function addTag(TagInterface $tag);

    /**
     * Remove the given tag from this entity.
     *
     * @param TagInterface $tag
     */
    public function removeTag(TagInterface $tag);

    /**
     * Check if this entity is tagged with the given tag.
     *
     * @param TagInterface $tag
     *
     * @return bool
     */
    public function hasTag(TagInterface $tag);
}
