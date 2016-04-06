<?php

class Aggregatable
{
    /**
     * Store of aggregated objects
     *
     */
    private $aggregated = array();

    /**
     * Aggregates objects
     *
     * @param string Classname
     */
    public function aggregate($class)
    {
        // Check an instance of this class has not already been aggregated
        if (array_key_exists($class, $this->aggregated))
        {
            throw new Exception(sprintf("Class already aggregated: %s", $class));
        }

        // Add a new instance of the class to the store
        $this->aggregated[$class] = new $class();
    }

    /**
     * Magic method - catch calls to undefined methods
     *
     * @param String method name
     * @param array Arguments
     */
    public function __call($method, $arguments)
    {
        // Loop through the aggregated objects
        foreach ($this->aggregated as $subject)
        {
            // Check each object to see if it defines the method
            if (method_exists($subject, (string) $method))
            {
                // Object defines the requested method, so call it and return the result
                return call_user_func_array(array($subject, (string) $method), $arguments);
            }
        }

        throw new Exception(sprintf("Method not found: %s", $method));
    }
}

?>