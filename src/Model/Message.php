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
 */
class Message implements MessageInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var ConversationInterface
     */
    protected $conversation;

    /**
     * @var PersonInterface
     */
    protected $sender;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @param ConversationInterface $conversation
     * @param PersonInterface       $sender
     */
    public function __construct(ConversationInterface $conversation, PersonInterface $sender, $body)
    {
        $this->conversation = $conversation;
        $this->sender = $sender;
        $this->body = $body;
        $this->date = new \DateTime();
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
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * {@inheritdoc}
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * {@inheritdoc}
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * {@inheritdoc}
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }
}
