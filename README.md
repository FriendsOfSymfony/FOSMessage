FOSMessage
==========

[![Build Status](https://travis-ci.org/FriendsOfSymfony/FOSMessage.svg?branch=master)](https://travis-ci.org/FriendsOfSymfony/FOSMessage)
[![Build status](https://ci.appveyor.com/api/projects/status/5h1rnsmk8hg4rkbf?svg=true)](https://ci.appveyor.com/project/tgalopin/fosmessage)


FOSMessage is a PHP 5.4+ framework-agnostic library providing a data structure
and common features to set up user-to-user messaging systems.

This library is currently in development.

This library is based on the ideas of the [FOSMessageBundle](https://github.com/FriendsOfSymfony/FOSMessageBundle)
and try to generalize them to use them in any context. It would be great to use
this creation from scratch as an opportunity to solve the issues
of the bundle, so please give us your ideas in the
[Github issues tracker](https://github.com/FriendsOfSymfony/FOSMessage/issues)!

Key points
----------

- Conversation-based messaging
- Multiple conversations participants support
- Very easy to implement (at least in most of the cases)
- Framework-agnotic
- Doctrine ORM and Mongo ODM support
- Not linked to user system implementation
- Optionnal tagging system to organize conversations
- Event system to let developer execute actions on key steps
- Implemented in framework-specific bundle / module
