The Sender
==========

The Sender let you start conversations and reply to them.

You can create it like this:

.. code-block:: php

    <?php
    $sender = new \FOS\Message\Sender($driver);

It provides 2 methods:


Start a conversation
^^^^^^^^^^^^^^^^^^^^

The method ``startConversation(PersonInterface $senderPerson, $recipient, $body, $subject = null)``
will start a conversation with a sender and a single or multiple recipient(s). A first
message will be posted in this conversation with a given body.

The method has 4 arguments:

- ``$senderPerson``: the user who started the conversation ;
- ``$recipient``: a single ``PersonInterface`` object or an array of ``PersonInterface`` ;
- ``$body``: the content of the first message of the conversation ;
- ``$subject``: in FOSMessage, subject is not required but you can provide one here ;

This method return the created conversation object (instance of ``ConversationInterface``).

For instance, in a controller it could look like this:

.. code-block:: php

    <?php

    class MessagingController
    {
        public function startAction(Request $request)
        {
            // ...
            $sender = new \FOS\Message\Sender($driver);

            if ($request->getMethod() == 'POST') {
                $data = ...; // Find the form data for instance ...

                $conversation = $sender->startConversation($this->getUser(), $data['recipient'], $data['body'], '');

                return $this->redirect('conversation_view', [ 'id' => $conversation->getId() ]);
            }

            return $this->render('form_start.html.twig');
        }
    }


Reply to a conversation
^^^^^^^^^^^^^^^^^^^^^^^

Once a user has started a conversation, other members could reply. The method
``sendMessage(ConversationInterface $conversation, PersonInterface $senderPerson, $body)``
does exactly that by replying to a given conversation, as a given sender with a given body.

The method has 3 arguments:

- ``$conversation``: the conversation in which the user want to post a reply ;
- ``$senderPerson``: the user who wrote the message ;
- ``$body``: the content of the reply ;

This method return the created message object (instance of ``MessageInterface``).

For instance, in a controller it could look like this:

.. code-block:: php

    <?php

    class MessagingController
    {
        public function replyAction($id)
        {
            // ...
            $repository = new \FOS\Message\Repository($driver);
            $conversation = $repository->getConversation($id);

            // Check access
            if (!$conversation->isPersonInConversation($this->getUser())) {
                throw new AccessDeniedHttpException();
            }

            $sender = new \FOS\Message\Sender($driver);

            if ($request->getMethod() == 'POST') {
                $data = ...; // Find the form data for instance ...

                $message = $sender->sendMessage($conversation, $this->getUser(), $data['body']);

                return $this->redirect('conversation_view', [ 'id' => $conversation->getId() ]);
            }

            return $this->render('form_reply.html.twig', [ 'conversation' => $conversation ]);
        }
    }
