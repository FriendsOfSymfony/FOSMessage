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
}
