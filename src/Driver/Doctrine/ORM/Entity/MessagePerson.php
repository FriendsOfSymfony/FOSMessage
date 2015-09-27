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
use FOS\Message\Model\MessagePerson as BaseMessagePerson;

/**
 * @ORM\Table(name="fos_message_messages_persons")
 * @ORM\Entity
 */
class MessagePerson extends BaseMessagePerson
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \FOS\Message\Model\MessageInterface
     *
     * @ORM\ManyToOne(targetEntity="FOS\Message\Driver\Doctrine\ORM\Entity\Message", cascade={"all"})
     */
    protected $message;

    /**
     * @var \FOS\Message\Model\PersonInterface
     *
     * @ORM\ManyToOne(targetEntity="FOS\Message\Model\PersonInterface", cascade={"all"})
     */
    protected $person;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="read_date", nullable=true)
     */
    protected $read;
}
