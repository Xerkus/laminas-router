<?php

/**
 * @see       https://github.com/laminas/laminas-router for the canonical source repository
 * @copyright https://github.com/laminas/laminas-router/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-router/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Mvc\Router;

use ArrayIterator;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Helper to test route factories.
 */
class FactoryTester
{
    /**
     * Test case to call assertions to.
     *
     * @var TestCase
     */
    protected $testCase;

    /**
     * Create a new factory tester.
     *
     * @param  TestCase $testCase
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * Test a factory.
     *
     * @param  string $className
     * @return void
     */
    public function testFactory($classname, array $requiredOptions, array $options)
    {
        // Test that the factory does not allow a scalar option.
        try {
            $classname::factory(0);
            $this->testCase->fail('An expected exception was not thrown');
        } catch (\Laminas\Mvc\Router\Exception\InvalidArgumentException $e) {
            $this->testCase->assertContains('factory expects an array or Traversable set of options', $e->getMessage());
        }

        // Test required options.
        foreach ($requiredOptions as $option => $exceptionMessage) {
            $testOptions = $options;

            unset($testOptions[$option]);

            try {
                $classname::factory($testOptions);
                $this->testCase->fail('An expected exception was not thrown');
            } catch (\Laminas\Mvc\Router\Exception\InvalidArgumentException $e) {
                $this->testCase->assertContains($exceptionMessage, $e->getMessage());
            }
        }

        // Create the route, will throw an exception if something goes wrong.
        $classname::factory($options);

        // Try the same with an iterator.
        $classname::factory(new ArrayIterator($options));
    }
}
