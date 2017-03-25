<?php


namespace Tests\eXpansion\Core\Services;


use eXpansion\Core\Services\Application;
use eXpansion\Core\Services\Console;
use eXpansion\Core\Services\DataProviderManager;
use eXpansion\Core\Services\PluginManager;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Tests\eXpansion\Core\TestCore;


class ApplicationDebugTest extends TestCore
{
    protected function setUp()
    {
        parent::setUp();

        $dataProviderMock = $this->createMock(Application\Dispatcher::class);
        $this->container->set('expansion.core.services.application.dispatch_logger', $dataProviderMock);

        $consoleMock = $this->createMock(Console::class);
        $this->container->set('expansion.core.services.console', $consoleMock);
    }


    public function testRun()
    {
        /** @var Application $application */
        $application = $this->container->get('expansion.core.services.application_debug');
        // We need to stop the application so that it doesen't run indefinitively.
        $application->stopApplication();

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get('expansion.core.services.application.dispatch_logger');
        $dataProviderMock->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [Application::EVENT_RUN, []],
                ['test', ['data']]
            );

        /** @var \PHPUnit_Framework_MockObject_MockObject $connectionMock */
        $connectionMock = $this->container->get('expansion.core.services.dedicated_connection');
        $connectionMock->expects($this->exactly(1))
            ->method('executeCallbacks')
            ->willReturn([['test', ['data']]]);

        $application->run();
    }
}
