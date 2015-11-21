The Repository
==============

The Repository is the basis of you messaging system. It let you fetch conversations and
messages.

You can create it like this:

.. code-block:: php

    <?php
    $repository = new \FOS\Message\Repository($driver);

It provides 4 methods:


List the converations of a given person
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Usually in a messaging system there is an "inbox": a list of conversations in which
the current user is participating.

To retrieve this list, the repository provides the method
``getPersonConversations(PersonInterface $person, $tag = null)``.

You can use it either without ``$tag`` object (we will talk about tags a bit later) to fetch
all the conversations of the given user.

Conversations will be sorted descending by date.

For instance, in a controller it could look like this:

.. code-block:: php

    <?php

    class MessagingController
    {
        public function inboxAction()
        {
            // ...
            $repository = new \FOS\Message\Repository($driver);
            $conversations = $repository->getPersonConversations($this->getUser());

            return $this->render('inbox.html.twig', [ 'conversations' => $conversations ]);
        }
    }


Find a conversation by its identifier
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The method ``getConversation($id)`` is quite easy to understand: it returns a single conversation
by its identifier (or null if none is found).

.. note::

    Note that the security is not handled by the library: you should check if your user is allowed
    to access the conversation.

For instance, in a controller it could look like this:

.. code-block:: php

    <?php

    class MessagingController
    {
        public function conversationAction($id)
        {
            // ...
            $repository = new \FOS\Message\Repository($driver);
            $conversation = $repository->getConversation($id);

            // Check access
            if (! $conversation->isPersonInConversation($this->getUser()) {
                throw new AccessDeniedHttpException();
            }

            return $this->render('conversation.html.twig', [ 'conversation' => $conversation ]);
        }
    }


List the messages of a given conversation
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

One you have a conversation, you will probably want to display its messages. To do so, you
have to use the method
``getMessages(ConversationInterface $conversation, $offset = 0, $limit = 20, $sortDirection = 'ASC')``.

This method has 4 arguments:

- the conversation ``$conversation`` of the messages ;
- the offset in the result set (for pagination) ;
- the limit of messages to get (for pagination) ;
- the sort direction to use (messages will be sorted by date) ;

For instance, in a controller it could look like this:

.. code-block:: php

    <?php

    class MessagingController
    {
        public function conversationAction($id)
        {
            // ...
            $repository = new \FOS\Message\Repository($driver);
            $conversation = $repository->getConversation($id);

            // Check access
            if (! $conversation->isPersonInConversation($this->getUser()) {
                throw new AccessDeniedHttpException();
            }

            $messages = $repository->getMessages($conversation);

            return $this->render('conversation.html.twig', [
                'conversation' => $conversation,
                'messages' => $messages,
            ]);
        }
    }


Find the link between a person and a conversation
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Sometimes you can need to retrieve the link between a user and a conversation
(for instance if you customized the entities and stored data in this link).

To do so, the repository provides the method
``getConversationPerson(ConversationInterface $conversation, PersonInterface $person)`` that
will return you an instance of ``FOS\Message\ModelConversationPersonInterface``.
