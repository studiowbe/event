<?php

namespace Studiow\Event\Test;

use Studiow\Event\EventManager;
use Studiow\Event\Event;
use PHPUnit_Framework_TestCase;

class EventManagerTest extends PHPUnit_Framework_TestCase
{

    public function testEventQueue()
    {
        $events = new EventManager();

        $str = "test";

        $events->attach("test_event", function(Event $ev) use (&$str) {
            $str = "first callback";
        });

        $events->attach("test_event", function(Event $ev) use (&$str) {
            $str = "last callback";
        });

        $events->trigger('test_event');
        $this->assertEquals("last callback", $str);
    }

    public function testEventQueuePriority()
    {
        $events = new EventManager();

        $str = "test";

        $events->attach("test_event", function(Event $ev) use (&$str) {
            $str = "last callback";
        }, -10);

        $events->attach("test_event", function(Event $ev) use (&$str) {
            $str = "first callback";
        });



        $events->trigger('test_event');
        $this->assertEquals("last callback", $str);
    }

    public function testEventQueueClearListeners()
    {
        $events = new EventManager();

        $str = "test";



        $events->attach("test_event", function(Event $ev) use (&$str) {
            $str = "first callback";
        });

        $events->attach("test_event", function(Event $ev) use (&$str) {
            $str = "last callback";
        });

        $events->clearListeners('test_event');
        $events->trigger('test_event');
        $this->assertEquals("test", $str);
    }

    public function testEventStopPropagation()
    {
        $events = new EventManager();

        $str = "test";

        $events->attach("test_event", function(Event $ev) use (&$str) {
            $str = "first callback";
            $ev->stopPropagation();
        });

        $events->attach("test_event", function(Event $ev) use (&$str) {
            $str = "last callback";
        });

        $events->trigger('test_event');
        $this->assertEquals("first callback", $str);
    }

    public function testEventDetach()
    {
        $events = new EventManager();

        $str = "test";

        $events->attach("test_event", function(Event $ev) use (&$str) {
            $str = "first callback";
        });

        $cb_last = function(Event $ev) use (&$str) {
            $str = "last callback";
        };

        $events->attach("test_event", $cb_last);
        $events->detach('test_event', $cb_last);

        $events->trigger('test_event');
        $this->assertEquals("first callback", $str);
    }

}
