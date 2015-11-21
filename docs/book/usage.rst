Usage
=====

Once you have configured Doctrine and your model, you are ready to use FOSMessage.

FOSMessage is organized around three components : the Repository that fetch conversations and messages,
the Sender that start conversations and send replies and the Tagger that let you (the developer) tag
conversations to retreive them in the future.

These three components are usually set up automatically in the context of a framework (by the dependency
injection). If you are not using a framework, you have to set up these components yourself.

For the moment, as only Doctrine ORM is available in FOSMessage, you have to use the
Doctrine ORM driver. In the future, other options will be available.


Choose your driver
------------------

The driver is the object linking the library to your persistance layer (Doctrine ORM, Propel, etc.).
Thus according to what persistance layer you are using, you have to choose a different driver
for FOSMessage.

For Doctrine ORM, you can create the driver as following using the entity manager configured in
the **Getting started** chapter:

.. code-block:: php

    <?php
    $driver = new \FOS\Message\Driver\Doctrine\ORM\DoctrineORMDriver($entityManager);


Use the components
------------------

.. toctree::
    :maxdepth: 2

    usage/repository
    usage/sender
