<?php
/**
 * File FactoryTest.php
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Framework\Core\Helpers\JobRunner;

use eXpansion\Framework\Core\Helpers\JobRunner\Factory;
use eXpansion\Framework\Core\Services\Console;
use oliverde8\AsynchronousJobs\Job;
use oliverde8\AsynchronousJobs\Job\CallbackCurl;
use oliverde8\AsynchronousJobs\JobRunner;
use Psr\Log\NullLogger;
use Tests\eXpansion\Framework\Core\TestCore;

class FactoryTest extends TestCore
{

    protected $triggered = false;

    protected function setUp()
    {
        parent::setUp(); 

        $this->triggered = false;

        $consoleMock = $this->createMock(Console::class);
        $this->container->set(Console::class, $consoleMock);

        $nullLogger = $this->createMock(NullLogger::class);
        $this->container->set(NullLogger::class, $nullLogger);
    }


    public function testGetRunner()
    {
        $factory = new Factory($this->container->get(NullLogger::class), $this->container->get(Console::class));
        $this->assertInstanceOf(JobRunner::class, $factory->getJobRunner());
    }

    public function testGetCurlJob()
    {
        $factory = new Factory($this->container->get(NullLogger::class), $this->container->get(Console::class));

        $jobRunner = $factory->getJobRunner();
        $job = $factory->createCurlJob('http://jsonplaceholder.typicode.com/posts', array($this, 'aCallback'));

        $this->assertInstanceOf(CallbackCurl::class, $job);

        // Start job and wait for it to end.
        $factory->startJob($job);
        $factory->onPostLoop();
        $jobRunner->waitForAll();

        $this->assertTrue($this->triggered);
    }

    public function aCallback(Job $curlJob)
    {
        $this->triggered = true;
    }
}
