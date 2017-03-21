<?php


namespace Tests\eXpansion\Core\Services;

use eXpansion\Core\DataProviders\ChatDataProvider;
use eXpansion\Core\DataProviders\Listener\ChatDataListenerInterface;
use eXpansion\Core\DataProviders\Listener\PlayerDataListenerInterface;
use eXpansion\Core\DataProviders\MapDataProvider;
use eXpansion\Core\DataProviders\PlayerDataProvider;
use eXpansion\Core\Exceptions\DataProvider\UncompatibleException;
use eXpansion\Core\Services\DataProviderManager;

use Tests\eXpansion\Core\TestCore;
use Tests\eXpansion\Core\TestHelpers\PlayerDataTrait;


class DataProviderManagerTest extends TestCore
{
    use PlayerDataTrait;

    protected function prepareProviders()
    {
        $dataProviderManager = $this->getDataProviderManager();

        $mockProvider = $this->createMock(ChatDataProvider::class);
        $this->container->set('dp1-1', $mockProvider);

        $mockProvider = $this->createMock(ChatDataProvider::class);
        $this->container->set('dp1-2', $mockProvider);

        $mockProvider = $this->createMock(MapDataProvider::class);
        $this->container->set('dp2-2', $mockProvider);

        $listner = ['onPlayerChat' => 'onPlayerChat'];

        $compatibilities = [];
        $compatibilities[] = $this->getCompatibility('TMStadium@nadeo', 'script', 'TimeAttack.script.txt');
        $dataProviderManager
            ->registerDataProvider('dp1-1', 'dp1', ChatDataListenerInterface::class, $compatibilities, $listner);

        $compatibilities = [];
        $compatibilities[] = $this->getCompatibility('TMStadium@nadeo');
        $dataProviderManager
            ->registerDataProvider('dp1-2', 'dp1', ChatDataListenerInterface::class, $compatibilities, $listner);

        $compatibilities = [];
        $compatibilities[] = $this->getCompatibility('TMStadium@nadeo2');
        $dataProviderManager
            ->registerDataProvider('dp2-2', 'dp2', ChatDataListenerInterface::class, $compatibilities, $listner);
    }

    public function testPreferenceDataProvider()
    {
        $dataProviderManager = $this->getDataProviderManager();
        $this->prepareProviders();

        $this->assertEquals(
            'dp1-1',
            $dataProviderManager->getCompatibleProviderId('dp1', 'TMStadium@nadeo', 'script', 'TimeAttack.script.txt')
        );

        $this->assertEquals(
            'dp1-2',
            $dataProviderManager->getCompatibleProviderId('dp1', 'TMStadium@nadeo', 'script2', 'TimeAttack.script.txt')
        );

        $this->assertEquals(
            'dp2-2',
            $dataProviderManager->getCompatibleProviderId('dp2', 'TMStadium@nadeo2', 'script2', 'TimeAttack.script.txt')
        );

        $this->assertNull(
            $dataProviderManager->getCompatibleProviderId('dp1', 'TMStadium@nadeo3', 'script2', 'TimeAttack.script.txt')
        );

        $this->assertTrue(
            $dataProviderManager->isProviderCompatible('dp1', 'TMStadium@nadeo', 'script2', 'TimeAttack.script.txt')
        );
    }

    public function testRegisterPlugin()
    {
        $this->prepareProviders();
        $dataProviderManager = $this->getDataProviderManager();
        $player = $this->getPlayer('test1', false);

        $pluginMock = $this->createMock(ChatDataListenerInterface::class);
        $this->container->set('p1', $pluginMock);

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get('dp1-1');
        $dataProviderMock->expects($this->once())->method('registerPlugin')->with('p1', $pluginMock);
        // $dataProviderMock->expects($this->once())->method('onChat')->withAnyParameters();

        $dataProviderMock->expects($this->once())->method('registerPlugin')->withConsecutive(['p1', $pluginMock]);

        $dataProviderManager->registerPlugin('dp1', 'p1', 'TMStadium@nadeo', 'script', 'TimeAttack.script.txt');
    }

    public function testRegisterWrongPlugin()
    {
        $this->prepareProviders();
        $dataProviderManager = $this->getDataProviderManager();
        $player = $this->getPlayer('test1', false);

        $pluginMock = $this->createMock(PlayerDataListenerInterface::class);
        $this->container->set('p1', $pluginMock);

        $this->expectException(UncompatibleException::class);

        $dataProviderManager->registerPlugin('dp1', 'p1', 'TMStadium@nadeo', 'script', 'TimeAttack.script.txt');
    }

    public function testDispatch()
    {
        $this->prepareProviders();
        $dataProviderManager = $this->getDataProviderManager();


        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get('dp1-1');
        $dataProviderMock->expects($this->once())->method('onPlayerChat')->withAnyParameters();
        $dataProviderMock = $this->container->get('dp1-2');
        $dataProviderMock->expects($this->once())->method('onPlayerChat')->withAnyParameters();

        $dataProviderManager->init();
        $dataProviderManager->dispatch('onPlayerChat', ['test', 'test2', false]);
    }


    protected function getCompatibility(
        $title,
        $mode = DataProviderManager::COMPATIBLE_ALL,
        $script = DataProviderManager::COMPATIBLE_ALL
    ){
        return [
            'title' => $title,
            'mode' => $mode,
            'script' => $script
        ];
    }

    /**
     *
     * @return DataProviderManager
     */
    protected function getDataProviderManager()
    {
        return $this->container->get('expansion.core.services.data_provider_manager');
    }
}