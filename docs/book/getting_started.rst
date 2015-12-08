Getting started
===============

Requirements
------------

FOSMessage only supports Doctrine ORM for the moment but it will support
Doctrine ODM in the future. Therefore, for now, you need Doctrine ORM:

.. code-block:: bash

    composer require doctrine/orm


Installation
------------

This bundle is available on Packagist. You can install it using Composer:

.. code-block:: bash

    composer require friendsofsymfony/message:1.0.x-dev

.. important::

    You should **not** use development versions in Composer: we are using it here
    only because the library is currently in development. When the library will be
    released, change that version to follow semantic versionning.


Configuration (wihout framework)
--------------------------------

Step 1: Set up your User model
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

Step 2: Configure the Doctrine entity manager
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


Configuration (using Symfony)
-----------------------------

While the FOSMessage bundle is not ready, you can still configure Symfony and Doctrine to
use the library in your project.

Step 1: Set up your User model
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

    namespace AppBundle\Entity;

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

Step 2: Configure the Doctrine entity manager
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You need to configure Doctrine for two things:

- use your User model as the entity for FOSMessage ;
- use the default entities provided by Doctrine ;

When you are using Symfony, you can configure the Doctrine entity manager through the
DoctrineBundle configuration:

.. code-block:: yml

    # app/config/config.yml

    doctrine:
        # ...

        orm:
            auto_generate_proxy_classes: "%kernel.debug%"
            naming_strategy: doctrine.orm.naming_strategy.underscore
            auto_mapping: true

            # The mappings to import the FOSMessage entities
            mappings:
                fos_message:
                    type: annotation
                    dir: %kernel.root_dir%/../vendor/friendsofsymfony/message/src/Driver/Doctrine/ORM/Entity
                    prefix: FOS\Message\Driver\Doctrine\ORM\Entity

            # User your user entity instead of the PersonInterface
            resolve_target_entities:
                FOS\Message\Model\PersonInterface: AppBundle\Entity\User

You also need to register a few services:

.. code-block:: yml

    # app/config/services.yml

    services:
        fos_message.driver:
            class: FOS\Message\Driver\Doctrine\ORM\DoctrineORMDriver
            arguments: [ "@doctrine.orm.entity_manager" ]

        fos_message.repository:
            class: FOS\Message\Repository
            arguments: [ "@fos_message.driver" ]

        fos_message.sender:
            class: FOS\Message\Sender
            arguments: [ "@fos_message.driver" ]


And then you will be able to use the components as following:

.. code-block:: php

    <?php

    namespace AppBundle\Controller;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    class DefaultController extends Controller
    {
        /**
         * @Route("/", name="homepage")
         */
        public function indexAction()
        {
            $repository = $this->get('fos_message.repository');
            $sender = $this->get('fos_message.sender');

            return $this->render('default/index.html.twig');
        }
    }


Now that you have a configured entity manager, you are ready to start using the library!
