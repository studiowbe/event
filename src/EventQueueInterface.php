<?php

namespace Studiow\Event;

use IteratorAggregate;
use Interop\Container\ContainerInterface;

interface EventQueueInterface extends IteratorAggregate
{

    /**
     * Attach a handler
     * 
     * @param mixed $callback
     * @param mixed $priority
     * @return $this
     */
    public function attach($callback, $priority = 0);

    /**
     * Remove a specific handler
     * 
     * @param mixed $callback
     * @return $this
     */
    public function detach($callback);

    /**
     * Clear all handlers
     * 
     * @return $this
     */
    public function clear();

    /**
     * Loop through all handlers
     * 
     * @param \Studiow\Event\EventInterface $event
     * @return $this
     */
    public function execute(EventInterface $event);

    /**
     * Set the container
     * 
     * @param \Interop\Container\ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null);

    /**
     * Get the container, if any
     * 
     * @return \Interop\Container\ContainerInterface|null
     */
    public function getContainer();
}
