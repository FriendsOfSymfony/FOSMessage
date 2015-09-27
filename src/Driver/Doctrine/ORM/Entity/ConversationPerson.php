<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Driver\Doctrine\ORM\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\Message\Model\ConversationPerson as BaseConversationPerson;

/**
 * @ORM\Table(name="fos_message_conversations_persons")
 * @ORM\Entity
 */
class ConversationPerson extends BaseConversationPerson
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \FOS\Message\Model\ConversationInterface
     *
     * @ORM\ManyToOne(
     *      targetEntity="FOS\Message\Driver\Doctrine\ORM\Entity\Conversation",
     *      inversedBy="conversationPersons",
     *      cascade={"all"}
     * )
     */
    protected $conversation;

    /**
     * @var \FOS\Message\Model\PersonInterface
     *
     * @ORM\ManyToOne(targetEntity="FOS\Message\Model\PersonInterface", cascade={"all"})
     */
    protected $person;

    /**
     * @var \FOS\Message\Model\TagInterface[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="FOS\Message\Driver\Doctrine\ORM\Entity\Tag")
     * @ORM\JoinTable(name="conversations_persons_tags")
     */
    protected $tags;
}
