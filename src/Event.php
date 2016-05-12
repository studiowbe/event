<?php

namespace Studiow\Event;

class Event implements EventInterface
{

    private $name;
    private $target = null;
    private $params = [];
    private $propagatingStopped = false;

    /**
     * Constructor 
     * 
     * @param string $name
     * @param object|null $target
     * @param array $params
     */
    public function __construct($name, $target = null, array $params = [])
    {
        $this->name = $name;
        $this->target = $target;
        $this->params = $params;
    }

    /**
     * Get the current event name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the current target
     * 
     * @return object|null
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Get associated parameters
     * 
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Indicate whether or not to stop propagating this event
     * 
     * @param bool $stop
     * @return \Studiow\Event\Event
     */
    public function stopPropagation($stop = true)
    {
        $this->propagatingStopped = (bool) $stop;
        return $this;
    }

    /**
     * Detect wheteher this event should be propagating
     * 
     * @return type
     */
    public function isPropagationStopped()
    {
        return $this->propagatingStopped;
    }

}
