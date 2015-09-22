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
(add your own by giving your opinion in the [Github issues](https://github.com/FriendsOfSymfony/FOSMessage/issues)!):

- Tagging system to organize conversations
- Event system to let developer execute actions on key steps
- Possobility of sending anonymous messages (used as notifications)

The final goal is to use this library as a basis for implementations in frameworks (mainly ZF and Symfony).
