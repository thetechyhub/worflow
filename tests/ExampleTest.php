<?php

namespace Thetechyhub\Workflow\Tests;

use Orchestra\Testbench\TestCase;
use Thetechyhub\Workflow\WorkflowServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [WorkflowServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
