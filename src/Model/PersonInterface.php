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
 * A person is a user receiving and sending messages.
 * You have to implement this interface on your user entity.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @author Christian Riesen <chris.riesen@gmail.com>
 */
interface PersonInterface
{
    /**
     * Return a unique identifier for this person.
     *
     * @return mixed Unique identifier, can vary depending on system used
     */
    public function getId();
}
