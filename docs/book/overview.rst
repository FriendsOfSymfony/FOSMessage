Overview
========

Installation
------------

Step 1: Download FOSMessage using Composer
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

This bundle is available on Packagist. You can install it using Composer:

.. code-block:: bash

    composer require friendsofsymfony/message:dev-master

.. important::

    You should **not** use ``dev-master`` version in Composer: we are using it here
    only because the library is currently in development. When the library will be
    released, change that version to follow semantic versionning.

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

Step 3: Configure the Doctrine entity manager
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You need to configure Doctrine for two things:

- use your User model as the entity for FOSMessage ;
- use the default entities provided by Doctrine ;

If you are not using a framework, you need to configure Doctrine manually
in order to get a usable EntityManager for FOSMessage.

Here is an example of configuration to help you do so:

.. code-block:: php

    <?php

    $config = \Doctrine\ORM\Tools\Setup::createConfiguration(true);

    /*
     * Tell Doctrine to use both your entities and the default entities from FOSMessage
     */
    $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver([
        __DIR__ . '/vendor/friendsofsymfony/message/src/Driver/Doctrine/ORM/Entity',
        __DIR__ . '/src',
    ], false));

    /*
     * If you want to use a debug logger
     */
    if ($logger) {
        $config->setSQLLogger($logger);
    }

    /*
     * Your database parameters
     */
    $dbParams = [
        'driver'   => 'pdo_mysql',
        'host'     => '127.0.0.1',
        'user'     => 'root',
        'password' => 'root',
        'dbname'   => 'fos_message',
    ];

    /*
     * Use the Doctrine event manager to use your User model instead of the FOSMessage interface
     * in FOSMessage driver
     */
    $rtel = new \Doctrine\ORM\Tools\ResolveTargetEntityListener();
    $rtel->addResolveTargetEntity('FOS\\Message\\Model\\PersonInterface', 'Entity\\User', []);

    $evm  = new \Doctrine\Common\EventManager();
    $evm->addEventListener(Doctrine\ORM\Events::loadClassMetadata, $rtel);

    /*
     * Finally, create the Doctrine EntityManager
     */
    $entityManager = \Doctrine\ORM\EntityManager::create($dbParams, $config, $evm);

If you are using a framework, the process might be different.


Step 4: Set up the library main components
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Once you have configured Doctrine and your model, you are ready to use FOSMessage.

FOSMessage is organized around three components : the Repository that fetch conversations and messages,
the Sender that start conversations and send replies and the Tagger that let you (the developer) tag
conversations to retreive them in the future.

These three components are usually set up automatically in the context of a framework (by the dependency
injection). If you are not using a framework, you have to set up these components yourself.

For the moment, as only Doctrine ORM is available in FOSMessage, you have to use the
Doctrine ORM driver. In the future, other options will be available.

Let's create the components to use them later:

.. code-block:: php

    <?php

    $driver = new \FOS\Message\Driver\Doctrine\ORM\DoctrineORMDriver($entityManager);

    // The Repository will let you fetch the messages and the conversations.
    $repository = new \FOS\Message\Repository($driver);

    // The Sender will let you start conversations and reply to them.
    $sender = new \FOS\Message\Sender($driver);

    // The Tagger will let you tag conversations to retrieve them easily in the future.
    $tagger = new \FOS\Message\Tagger($driver, $repository);
