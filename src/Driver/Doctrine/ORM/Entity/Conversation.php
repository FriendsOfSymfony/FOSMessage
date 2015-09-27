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
use FOS\Message\Model\Conversation as BaseConversation;

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
     * @var \FOS\Message\Model\ConversationPersonInterface[]
     *
     * @ORM\OneToMany(
     *      targetEntity="FOS\Message\Driver\Doctrine\ORM\Entity\ConversationPerson",
     *      mappedBy="conversation",
     *      cascade={"all"}
     * )
     */
    protected $persons;
}
