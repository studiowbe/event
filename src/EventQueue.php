<?php

namespace Studiow\Event;

use SplPriorityQueue;
use Interop\Container\ContainerInterface;

class EventQueue implements EventQueueInterface
{

    private $callbacks = [];

    /**
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * Attach a handler
     * 
     * @param mixed $callback
     * @param mixed $priority
     * @return EventQueue
     */
    public function attach($callback, $priority = 0)
    {
        $this->callbacks[] = ['callback' => $callback, 'priority' => $priority];
        return $this;
    }

    /**
     * Remove a specific handler
     * 
     * @param mixed $callback
     * @return \Studiow\Event\EventQueue
     */
    public function detach($callback)
    {
        $this->callbacks = array_filter($this->callbacks, function($handler) use($callback) {
            return $handler['callback'] != $callback;
        });

        return $this;
    }

    /**
     * Clear all handlers
     * 
     * @return \Studiow\Event\EventQueue
     */
    public function clear()
    {
        $this->callbacks = [];
        return $this;
    }

    /**
     * Loop through all handlers
     * 
     * @param \Studiow\Event\EventInterface $event
     * @return \Studiow\Event\EventQueue
     */
    public function execute(EventInterface $event)
    {
        foreach ($this->getIterator() as $callback) {
            if ($event->isPropagationStopped()) {
                return $this;
            }
            call_user_func_array($this->expandCallback($callback), [$event]);
        }
        return $this;
    }

    /**
     * Add support for classname::method style callbacks, use container if possible
     * 
     * @param mixed $callback
     * @return callable
     */
    private function expandCallback($callback)
    {

        if (is_string($callback) && (bool) strpos($callback, '::') !== false) {
            $callback = explode('::', $callback);
        }

        if (is_array($callback) && isset($callback[0])) {
            if (is_string($callback[0])) {
                $callback[0] = $this->getInstanceOf($callback[0]);
            }

            $callback = [$callback[0], $callback[1]];
        }


        return $callback;
    }

    /**
     * Get an instance. From the container if possible
     * 
     * @param string $classname
     * @return object
     */
    private function getInstanceOf($classname)
    {
        if ((bool) $this->getContainer() && $this->getContainer()->has($classname)) {
            return $this->getContainer()->get($classname);
        } else {
            return new $classname;
        }
    }

    /**
     * Get the internal iterator
     * 
     * @return SplPriorityQueue
     */
    public function getIterator()
    {
        $queue = new SplPriorityQueue();
        foreach ($this->callbacks as $handler) {

            $queue->insert($handler['callback'], $handler['priority']);
        }
        return $queue;
    }

    /**
     * Set the container
     * 
     * @param \Interop\Container\ContainerInterface|null $container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get the container, if any
     * 
     * @return \Interop\Container\ContainerInterface|null
     */
    public function setContainer(\Interop\Container\ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
