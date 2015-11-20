Overview
========

Installation
------------

Step 1: Download FOSMessage using Composer
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This bundle is available on Packagist. You can install it using Composer:

.. code-block:: bash

    composer require friendsofsymfony/message:~1.0@dev

Step 2: Set up your User model
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

FOSMessage provides a flexible set of tools organized around three main entites:
conversations, messages and persons. Default entities are provided for conversations
and messages but you need to configure the library to use your User model.

FOSMessage requires that your user class implement `PersonInterface`. This
library does not have any direct dependencies to any particular user system,
except that it must implement the above interface.

Your user class may look something like the following:

.. code-block:: php

    <?php
    use Doctrine\ORM\Mapping as ORM;
    use FOS\Message\Model\PersonInterface;

    /**
     * @ORM\Entity
     */
    class User implements PersonInterface
    {
        public function getId()
        {
            return $this->id;
        }

        // Your code ...
    }

Step 3: Set up the library main components
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

FOSMessage is organized around three components : the Repository that fetch conversations and messages,
the Sender that start conversations and send replies and the Tagger that let you (the developer) tag
conversation to retreive them in the future.

These three components are usually set up automatically in the context of a framework (by the dependency
injection). If you are not using a framework, you have to set up these components yourself.
