<?php

/*
 * This file is part of the FOSMessage library.
 *
 * (c) Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\Message\Tests;

use FOS\Message\Driver\DriverInterface;
use FOS\Message\RepositoryInterface;
use FOS\Message\Tagger;
use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;

class TaggerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DriverInterface|MockInterface
     */
    private $driver;

    /**
     * @var RepositoryInterface|MockInterface
     */
    private $repository;

    /**
     * @var Tagger
     */
    private $tagger;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->driver = Mockery::mock('FOS\Message\Driver\DriverInterface');
        $this->repository = Mockery::mock('FOS\Message\RepositoryInterface');
        $this->tagger = new Tagger($this->driver, $this->repository);
    }

    public function testAddNewTag()
    {
        $person = Mockery::mock('FOS\Message\Model\PersonInterface');
        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');
        $tag = Mockery::mock('FOS\Message\Model\TagInterface');

        $conversationPerson = Mockery::mock('FOS\Message\Model\ConversationPersonInterface');
        $conversationPerson->shouldReceive('hasTag')->with($tag)->once()->andReturn(false);
        $conversationPerson->shouldReceive('addTag')->with($tag)->once()->andReturnSelf();

        $this->repository->shouldReceive('getConversationPerson')
            ->once()
            ->with($conversation, $person)
            ->andReturn($conversationPerson);

        $this->driver->shouldReceive('persistConversationPerson')
            ->once()
            ->with($conversationPerson)
            ->andReturn(true);

        $this->driver->shouldReceive('flush')
            ->once()
            ->withNoArgs()
            ->andReturn(true);

        $this->assertTrue($this->tagger->addTag($conversation, $person, $tag));
    }

    public function testAddExistingTag()
    {
        $person = Mockery::mock('FOS\Message\Model\PersonInterface');
        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');
        $tag = Mockery::mock('FOS\Message\Model\TagInterface');

        $conversationPerson = Mockery::mock('FOS\Message\Model\ConversationPersonInterface');
        $conversationPerson->shouldReceive('hasTag')->with($tag)->once()->andReturn(true);

        $this->repository->shouldReceive('getConversationPerson')
            ->once()
            ->with($conversation, $person)
            ->andReturn($conversationPerson);

        $this->assertFalse($this->tagger->addTag($conversation, $person, $tag));
    }

    public function testRemoveExistingTag()
    {
        $person = Mockery::mock('FOS\Message\Model\PersonInterface');
        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');
        $tag = Mockery::mock('FOS\Message\Model\TagInterface');

        $conversationPerson = Mockery::mock('FOS\Message\Model\ConversationPersonInterface');
        $conversationPerson->shouldReceive('hasTag')->with($tag)->once()->andReturn(true);
        $conversationPerson->shouldReceive('removeTag')->with($tag)->once()->andReturn(true);

        $this->repository->shouldReceive('getConversationPerson')
            ->once()
            ->with($conversation, $person)
            ->andReturn($conversationPerson);

        $this->driver->shouldReceive('persistConversationPerson')
            ->once()
            ->with($conversationPerson)
            ->andReturn(true);

        $this->driver->shouldReceive('flush')
            ->once()
            ->withNoArgs()
            ->andReturn(true);

        $this->assertTrue($this->tagger->removeTag($conversation, $person, $tag));
    }

    public function testRemoveNotExistingTag()
    {
        $person = Mockery::mock('FOS\Message\Model\PersonInterface');
        $conversation = Mockery::mock('FOS\Message\Model\ConversationInterface');
        $tag = Mockery::mock('FOS\Message\Model\TagInterface');

        $conversationPerson = Mockery::mock('FOS\Message\Model\ConversationPersonInterface');
        $conversationPerson->shouldReceive('hasTag')->with($tag)->once()->andReturn(false);

        $this->repository->shouldReceive('getConversationPerson')
            ->once()
            ->with($conversation, $person)
            ->andReturn($conversationPerson);

        $this->assertFalse($this->tagger->removeTag($conversation, $person, $tag));
    }
}
