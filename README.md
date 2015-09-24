FOSMessage
==========

FOSMessage is a PHP 5.4+ framework-agnostic library providing a data structure
and common features to set up user to user messaging systems.

This library is still being designed: while the basic use-cases are already well known
(extracted from the bundle), the library aims to be much more generic so it can be used
in other contexts.

If you have any idea or use case about what would like to have in the library, please
give your opinion in the [Github issues](https://github.com/FriendsOfSymfony/FOSMessage/issues).
It would be a great way to start to have some ideas for developers that plan to use it.

Key points / Aims of the library
--------------------------------

- Conversation-based messaging
- Multiple conversations participants support
- Very easy to implement (at least in most of the cases)
- Framework-agnotic
- Multiple persistance layers support (ORM and ODM, [probably others](https://github.com/FriendsOfSymfony/FOSMessage/issues/1))
- Not linked to user system implementation

Some ideas emerged along the discussions
(add your own by giving your opinion in the [Github issues](https://github.com/FriendsOfSymfony/FOSMessage/issues)):

- Tagging system to organize conversations
- Event system to let developer execute actions on key steps
- Possobility of sending anonymous messages (used as notifications)

The final goal is to use this library as a basis for implementations in frameworks (mainly ZF and Symfony).

Usage ideas
-----------

While the code is not written, we worked a lot on how we would like to interact with the library from a developer
point of view.

We agreed on something like this:

(if you have ideas / issues with this, please open an issue or even better create a PR !):
  
``` php
/*
 * Create the Doctrine entity manager
 */
$entityManager = createEm();

/*
 * Create the FOSMessage driver
 */
$driver = new \FOS\Message\Driver\DoctrineORM\DoctrineORMDriver(
    $entityManager,
    'Conversation',             // Your Conversation entity here
    'ConversationPerson',       // Your ConversationPerson entity here
    'Message',                  // Your Message entity here
    'MessagePerson'             // Your MessagePerson entity here
);

/*
 * Create the repository using the driver
 */
$repository = new \FOS\Message\Repository($driver);

/*
 * Return all the conversation in which the given user is a member
 * (an instance of ConversationPerson linked the user and the conversation).
 *
 * $user is an instance of PersonInterface.
 *
 * You can optionally pass a tag as a string or a TagInterface object to filter
 * conversations by this tag.
 *
 * Internally, this method join the ConversationPerson and PersonInterface entities
 * so listing them won't trigger a new SQL query.
 *
 * This method returns an array of conversations: ConversationInterface[]
 */
$conversations = $repository->getPersonConversations($user, $tag = null);

/*
 * Return a specific conversation with identifier $id or
 * null if the conversation is not found.
 *
 * Internally, this method join the ConversationPerson and PersonInterface entities
 * so listing them won't trigger a new SQL query.
 *
 * This method returns an instance of ConversationInterface or null.
 */
$conversation = $repository->getConversation($id);

/*
 * Return an ordered list of messages from the conversation $conversation.
 *
 * The parameters $offset and $limit let you paginate the messages, and the
 * parameter $sortDirection let you change the order direction (messages are
 * ordered by date either ASC or DESC according to this parameter).
 *
 * This method returns an array of messages from the conversation: MessageInterface[]
 */
$messages = $repository->getMessages($conversation, $offset = 0, $limit = 20, $sortDirection = 'ASC');


/*
 * Create the sender using the repository and the driver
 */
$sender = new \FOS\Message\Sender($repository, $driver);

/*
 * Start a conversation and send a first message in it.
 *
 * The conversation is started by $senderPerson (instance of PersonInterface)
 * and a first message is send to $recipientPerson (either an instance of PersonInterface
 * or an array of PersonInterface instances for multiple recipients).
 *
 * The $subject is a subject for the conversation that can be retreived
 * afterwards to display in lists.
 *
 * The $body is the content of the first message sent by $senderPerson to $recipientPerson.
 *
 * This method returns the created conversation: ConversationInterface
 */
$sender->startConversation($senderPerson, $recipientPerson, $body, $subject = null);

/*
 * Send a reply to $conversation.
 *
 * A new message is created in the conversation, with $body as message content
 * and sent by $senderPerson.
 *
 * This method returns the created message: MessageInterface
 */
$sender->sendMessage($conversation, $senderPerson, $body);


/*
 * Create the tagger using the repository and the driver
 */
$tagger = new \FOS\Message\Tagger($driver, $repository);

// The conversation to tag
$conversation = $repository->getConversation($id);

/*
 * Add a $tag on a $conversation for a $user
 */
$tagger->addTag($conversation, $user, $tag);

/*
 * Check if the $conversation has the $tag on it for the $user
 */
$tagger->hasTag($conversation, $user, $tag);

/*
 * Remove a $tag from a $conversation for a $user
 */
$tagger->removeTag($conversation, $user, $tag);


/*
 * Once tagged, you can filter conversations lists by tag
 */
$conversations = $repository->getPersonConversations($user, $tag = null);
```
