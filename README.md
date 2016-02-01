FOSMessage
==========

[![Build Status](https://travis-ci.org/FriendsOfSymfony/FOSMessage.svg?branch=master)](https://travis-ci.org/FriendsOfSymfony/FOSMessage)
[![Build status](https://ci.appveyor.com/api/projects/status/5h1rnsmk8hg4rkbf?svg=true)](https://ci.appveyor.com/project/tgalopin/fosmessage)

FOSMessage is a PHP 5.4+ framework-agnostic library providing a data structure
and common features to set up user-to-user messaging systems.

You can think of it as a model for your messaging features : it will take care of the consistency
of the data for you in order to easily create a full-featured messaging system.

> *Note* : This library is currently in development. You can test it in your project
> (the Composer installation process is very simple), but you should not use it in production
> for the moment.

This library is based on concepts shared by most modern frameworks (dependency injection,
event dispatching, abstract data drivers, etc.) and therefore, it’s very easy to set it up in any
kind of context.

If you want to set it up in Symfony, *FOSMesageBundle* is being developed in a new version
(not ready yet).

Documentation
-------------

You can [read the documentation here](http://fosmessage.readthedocs.org).

Usage example
-------------

An implementation example is available on Github:
[tgalopin/FOSMessage-demo](https://github.com/tgalopin/FOSMessage-demo).

Key features
------------

- Conversation-based messaging
- Multiple conversations participants support
- Very easy to implement (at least in most of the cases)
- Framework-agnotic
- Doctrine ORM and Mongo ODM support
- Not linked to user system implementation
- Optionnal tagging system to organize conversations
- Event system to let developer execute actions on key steps
- Implemented in framework-specific bundle / module
- PHP7 and HHVM support
