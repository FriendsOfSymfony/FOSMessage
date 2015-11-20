Overview
========

Installation
------------

Step 1: Download FOSMessage using Composer
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This bundle is available on Packagist. You can install it using Composer:

.. code-block:: bash

<<<<<<< HEAD
    composer require friendsofsymfony/message:~1.0@dev
=======
    composer require friendsofsymfony/message:dev-master

.. important::

    You should **not** use ``dev-master`` version in Composer: we are using it here
    only because the library is currently in development. When the library will be
    released, change that version to follow semantic versionning.
>>>>>>> Update documentation and package name

Step 2: Set up your User model
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. note::

    For the moment, only Doctrine ORM is supported. Doctrine ODM will be available soon.

FOSMessage provides a flexible set of tools organized around three main entites:
conversations, messages and persons.

The library provides default entities for conversations and messages and they will
be enough for the beginning (see *Customize the default entities* to learn more).

However, you need to configure the library to tell it what your User model is.
FOSMessage requires that your user class implement ``PersonInterface``. This
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
