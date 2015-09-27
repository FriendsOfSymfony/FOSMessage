<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Driver\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use FOS\Message\Driver\AbstractDriver;
use FOS\Message\Model\ConversationInterface;
use FOS\Message\Model\ConversationPersonInterface;
use FOS\Message\Model\MessageInterface;
use FOS\Message\Model\MessagePersonInterface;

/**
 * Abstract driver for Doctrine persistence managers (ORM and ODM).
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
abstract class AbstractDoctrineDriver extends AbstractDriver
{
    /**
     * @var ObjectManager|EntityManager
     */
    protected $objectManager;

    /**
     * Constructor.
     *
     * @param ObjectManager $objectManager
     * @param string        $conversationClass
     * @param string        $conversationPersonClass
     * @param string        $messageClass
     * @param string        $messagePersonClass
     */
    public function __construct(
        ObjectManager $objectManager,
        $conversationClass,
        $conversationPersonClass,
        $messageClass,
        $messagePersonClass
    ) {
        parent::__construct($conversationClass, $conversationPersonClass, $messageClass, $messagePersonClass);

        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritdoc}
     */
    public function persistConversation(ConversationInterface $conversation)
    {
        $this->objectManager->persist($conversation);
    }

    /**
     * {@inheritdoc}
     */
    public function persistConversationPerson(ConversationPersonInterface $conversationPerson)
    {
        $this->objectManager->persist($conversationPerson);
    }

    /**
     * {@inheritdoc}
     */
    public function persistMessage(MessageInterface $message)
    {
        $this->objectManager->persist($message);
    }

    /**
     * {@inheritdoc}
     */
    public function persistMessagePerson(MessagePersonInterface $messagePerson)
    {
        $this->objectManager->persist($messagePerson);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->objectManager->flush();
    }
}
