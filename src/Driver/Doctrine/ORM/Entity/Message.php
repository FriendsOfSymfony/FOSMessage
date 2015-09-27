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
use FOS\Message\Model\Message as BaseMessage;

/**
 * @ORM\Table(name="fos_message_messages")
 * @ORM\Entity
 */
class Message extends BaseMessage
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
     *      inversedBy="persons",
     *      cascade={"all"}
     * )
     */
    protected $conversation;

    /**
     * @var \FOS\Message\Model\PersonInterface
     *
     * @ORM\ManyToOne(targetEntity="FOS\Message\Model\PersonInterface", cascade={"all"})
     */
    protected $sender;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $date;
}
