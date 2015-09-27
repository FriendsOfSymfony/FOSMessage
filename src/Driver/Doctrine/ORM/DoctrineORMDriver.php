<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Driver\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use FOS\Message\Driver\Doctrine\AbstractDoctrineDriver;
use FOS\Message\Model\ConversationInterface;
use FOS\Message\Model\PersonInterface;
use FOS\Message\Model\TagInterface;

/**
 * Driver for Doctrine ORM.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class DoctrineORMDriver extends AbstractDoctrineDriver
{
    /**
     * Constructor.
     *
     * @param EntityManager $objectManager
     * @param string        $conversationClass
     * @param string        $conversationPersonClass
     * @param string        $messageClass
     * @param string        $messagePersonClass
     */
    public function __construct(
        EntityManager $objectManager,
        $conversationClass = 'FOS\Message\Driver\Doctrine\ORM\Entity\Conversation',
        $conversationPersonClass = 'FOS\Message\Driver\Doctrine\ORM\Entity\ConversationPerson',
        $messageClass = 'FOS\Message\Driver\Doctrine\ORM\Entity\Message',
        $messagePersonClass = 'FOS\Message\Driver\Doctrine\ORM\Entity\MessagePerson'
    ) {
        parent::__construct(
            $objectManager,
            $conversationClass,
            $conversationPersonClass,
            $messageClass,
            $messagePersonClass
        );
    }

    /**
     * {@inheritdoc}
     */
    public function findPersonConversations(PersonInterface $person, TagInterface $tag = null)
    {
        $qb = $this->createConversationQueryBuilder();

        // Person filter
        $personSubquery = $this->objectManager->createQueryBuilder()
            ->select('IDENTITY(pfcp.conversation)')
            ->from($this->getConversationPersonClass(), 'pfcp')
            ->where('pfcp.person = :person')
            ->getDQL();

        $qb->where($qb->expr()->in('c.id', $personSubquery));
        $qb->setParameter('person', $person->getId());

        // Tag filter
        if ($tag !== null) {
            $tag = $tag->getName();

            $tagSubquerey = $this->objectManager->createQueryBuilder()
                ->select('IDENTITY(tfcp.conversation)')
                ->from($this->getConversationPersonClass(), 'tfcp')
                ->leftJoin('tfcp.tags', 'tft')
                ->where('tft.name = :tag')
                ->getDQL();

            $qb->andWhere($qb->expr()->in('c.id', $tagSubquerey));
            $qb->setParameter('tag', $tag);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findConversationPerson(ConversationInterface $conversation, PersonInterface $person)
    {
        return $this->objectManager->createQueryBuilder()
            ->select('cp', 'c', 'p', 't')
            ->from($this->getConversationPersonClass(), 'cp')
            ->innerJoin('cp.conversation', 'c')
            ->innerJoin('cp.person', 'p')
            ->leftJoin('cp.tags', 't')
            ->where('c.id = :conversation')
            ->setParameter('conversation', $conversation->getId())
            ->andWhere('p.id = :person')
            ->setParameter('person', $person->getId())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findConversation($id)
    {
        return $this->createConversationQueryBuilder()
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findMessages(ConversationInterface $conversation, $offset = 0, $limit = 20, $sortDirection = 'ASC')
    {
        $qb = $this->objectManager->createQueryBuilder()
            ->select('m', 's')
            ->from($this->getMessageClass(), 'm')
            ->leftJoin('m.sender', 's')
            ->where('m.conversation = :conversation')
            ->setParameter('conversation', $conversation->getId())
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->orderBy('m.date', $sortDirection);

        return $qb->getQuery()->getResult();
    }

    /**
     * Create a conversation query builder with optimized joins.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createConversationQueryBuilder()
    {
        return $this->objectManager->createQueryBuilder()
            ->select('c', 'cp', 'p', 't')
            ->from($this->getConversationClass(), 'c')
            ->leftJoin('c.persons', 'cp')
            ->leftJoin('cp.person', 'p')
            ->leftJoin('cp.tags', 't');
    }
}
