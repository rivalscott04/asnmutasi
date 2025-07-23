<?php

namespace Core\Support;

/**
 * Dependency Injection Container
 * Simple DI container dengan auto-resolve
 */
class Container
{
    private static $bindings = [];
    private static $instances = [];
    private static $singletons = [];
    
    /**
     * Bind a class or interface to implementation
     */
    public static function bind($abstract, $concrete = null, $singleton = false)
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        
        self::$bindings[$abstract] = [
            'concrete' => $concrete,
            'singleton' => $singleton
        ];
    }
    
    /**
     * Bind as singleton
     */
    public static function singleton($abstract, $concrete = null)
    {
        self::bind($abstract, $concrete, true);
    }
    
    /**
     * Register an existing instance as singleton
     */
    public static function instance($abstract, $instance)
    {
        self::$instances[$abstract] = $instance;
    }
    
    /**
     * Resolve a class from container
     */
    public static function resolve($abstract)
    {
        // Check if instance already exists
        if (isset(self::$instances[$abstract])) {
            return self::$instances[$abstract];
        }
        
        // Check if binding exists
        if (isset(self::$bindings[$abstract])) {
            $binding = self::$bindings[$abstract];
            $concrete = $binding['concrete'];
            
            // If singleton and already resolved
            if ($binding['singleton'] && isset(self::$singletons[$abstract])) {
                return self::$singletons[$abstract];
            }
            
            $instance = self::build($concrete);
            
            // Store singleton
            if ($binding['singleton']) {
                self::$singletons[$abstract] = $instance;
            }
            
            return $instance;
        }
        
        // Try to auto-resolve
        return self::build($abstract);
    }
    
    /**
     * Build an instance of the given class
     */
    private static function build($concrete)
    {
        // If concrete is a closure
        if ($concrete instanceof \Closure) {
            return $concrete(new static());
        }
        
        // If concrete is a string (class name)
        if (is_string($concrete)) {
            if (!class_exists($concrete)) {
                throw new \Exception("Class {$concrete} does not exist");
            }
            
            $reflection = new \ReflectionClass($concrete);
            
            // Check if class is instantiable
            if (!$reflection->isInstantiable()) {
                throw new \Exception("Class {$concrete} is not instantiable");
            }
            
            $constructor = $reflection->getConstructor();
            
            // If no constructor, just create instance
            if ($constructor === null) {
                return new $concrete();
            }
            
            // Resolve constructor dependencies
            $dependencies = self::resolveDependencies($constructor->getParameters());
            
            return $reflection->newInstanceArgs($dependencies);
        }
        
        return $concrete;
    }
    
    /**
     * Resolve constructor dependencies
     */
    private static function resolveDependencies($parameters)
    {
        $dependencies = [];
        
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            
            if ($type === null) {
                // No type hint, check for default value
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Cannot resolve parameter {$parameter->getName()}");
                }
            } else {
                // Type hinted parameter
                $className = $type->getName();
                
                if ($type->isBuiltin()) {
                    // Built-in type, check for default value
                    if ($parameter->isDefaultValueAvailable()) {
                        $dependencies[] = $parameter->getDefaultValue();
                    } else {
                        throw new \Exception("Cannot resolve built-in type {$className}");
                    }
                } else {
                    // Class type, resolve from container
                    $dependencies[] = self::resolve($className);
                }
            }
        }
        
        return $dependencies;
    }
    
    /**
     * Check if abstract is bound
     */
    public static function bound($abstract)
    {
        return isset(self::$bindings[$abstract]) || isset(self::$instances[$abstract]);
    }
    
    /**
     * Clear all bindings and instances
     */
    public static function flush()
    {
        self::$bindings = [];
        self::$instances = [];
        self::$singletons = [];
    }
}