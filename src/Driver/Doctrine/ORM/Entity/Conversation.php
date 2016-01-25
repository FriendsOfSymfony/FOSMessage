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

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\Message\Model\Conversation as BaseConversation;
use FOS\Message\Model\ConversationPersonInterface;
use FOS\Message\Model\MessageInterface;

/**
 * @ORM\Table(name="fos_message_conversations")
 * @ORM\Entity
 */
class Conversation extends BaseConversation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $subject;

    /**
     * @var MessageInterface[]|Collection
     *
     * @ORM\OneToMany(
     *      targetEntity="FOS\Message\Driver\Doctrine\ORM\Entity\Message",
     *      mappedBy="conversation",
     *      cascade={"all"}
     * )
     */
    protected $messages;

    /**
     * @var ConversationPersonInterface[]|Collection
     *
     * @ORM\OneToMany(
     *      targetEntity="FOS\Message\Driver\Doctrine\ORM\Entity\ConversationPerson",
     *      mappedBy="conversation",
     *      cascade={"all"}
     * )
     */
    protected $persons;
}
