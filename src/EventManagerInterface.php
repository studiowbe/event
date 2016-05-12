<?php

namespace Studiow\Event;
use Interop\Container\ContainerInterface;
interface EventManagerInterface
{

    /**
     * Attach a handler to a specific queue
     * 
     * @param string $name
     * @param mixed $callback
     * @param mixed $priority
     * @return $this
     */
    public function attach($name, $callback, $priority = 0);

    /**
     * Remove a specific handler from a specific queue
     * 
     * @param string $name
     * @param mixed $callback
     * @return $this
     */
    public function detach($name, $callback);

    /**
     *  Remove all handlers from a specific queue
     * 
     * @param string $name
     * @return $this
     *
     */
    public function clearListeners($name);

    /**
     * Execute all handlers for a specific event/event name
     * 
     * @param \Studiow\Event\EventInterface|string $event
     * @param mixed $target
     * @param array $params
     * @return $this
     */
    public function trigger($event, $target = null, $params = []);

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
