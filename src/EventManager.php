<?php

namespace Studiow\Event;

use LogicException;
use Interop\Container\ContainerInterface;

class EventManager implements EventManagerInterface
{

    private $queues = [];

    /**
     * Constructor
     * 
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->setContainer($container);
    }

    /**
     * Get the current queue for $name, or create a new one
     * 
     * @param string $name
     * @return \Studiow\Event\EventQueue
     */
    private function getQueue($name)
    {

        if (!$this->hasQueue($name)) {
            $this->queues[$name] = new EventQueue();
        }

        return $this->queues[$name];
    }

    /**
     * Check if a queue for $name exists
     * 
     * @param string $name
     * @return bool
     */
    private function hasQueue($name)
    {
        return array_key_exists($name, $this->queues);
    }

    /**
     * Attach a handler to a specific queue
     * 
     * @param string $name
     * @param mixed $callback
     * @param mixed $priority
     * @return \Studiow\Event\EventManager
     */
    public function attach($name, $callback, $priority = 0)
    {
        $this->getQueue($name)->attach($callback, $priority);
        return $this;
    }

    /**
     * Remove a specific handler from a specific queue
     * 
     * @param string $name
     * @param mixed $callback
     * @return \Studiow\Event\EventManager
     */
    public function detach($name, $callback)
    {
        if ($this->hasQueue($name)) {
            $this->getQueue($name)->detach($callback);
        }
        return $this;
    }

    /**
     *  Remove all handlers from a specific queue
     * 
     * @param string $name
     * @return \Studiow\Event\EventManager
     *
     */
    public function clearListeners($name)
    {
        if ($this->hasQueue($name)) {
            $this->getQueue($name)->clear();
        }
        return $this;
    }

    /**
     * Execute all handlers for a specific event/event name
     * 
     * @param \Studiow\Event\EventInterface|string $event
     * @param mixed $target
     * @param array $params
     * @return \Studiow\Event\EventManager
     */
    public function trigger($event, $target = null, $params = [])
    {
        if (!($event instanceof EventInterface)) {
            if (is_string($event)) {
                $event = new Event($event, $target, $params);
            } else {
                throw new LogicException("\$event must be a string, or implement \\Studiow\\Event\\EventInterface");
            }
        }

        if ($this->hasQueue($event->getName())) {
            $this->getQueue($event->getName())->setContainer($this->getContainer());
            $this->getQueue($event->getName())->execute($event);
        }

        return $this;
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
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
