<?php

namespace Studiow\Event;

interface EventInterface
{

    /**
     * Constructor 
     * 
     * @param string $name
     * @param object|null $target
     * @param array $params
     */
    public function __construct($name, $target = null, array $params = []);

    /**
     * Get the current event name
     * 
     * @return string
     */
    public function getName();

    /**
     * Get the current target
     * 
     * @return object|null
     */
    public function getTarget();

    /**
     * Get associated parameters
     * 
     * @return array
     */
    public function getParams();

    /**
     * Stop propagating this event
     * 
     * @param bool $stop
     * @return \Studiow\Event\Event
     */
    public function stopPropagation($stop = true);

    /**
     * Detect wheteher this event should be propagating
     * 
     * @return bool
     */
    public function isPropagationStopped();
}
