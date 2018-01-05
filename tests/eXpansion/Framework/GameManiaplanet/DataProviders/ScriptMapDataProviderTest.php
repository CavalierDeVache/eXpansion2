<?php
/**
 * File MapDataProviderTest.php
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use eXpansion\Framework\GameManiaplanet\DataProviders\ScriptMapDataProvider;
use Maniaplanet\DedicatedServer\Structures\Map;

class ScriptMapDataProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockMapStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlugin;

    /** @var ScriptMapDataProvider */
    protected $mapProvider;

    protected function setUp()
    {
        parent::setUp(); 
        $this->mockMapStorage = $this->getMockBuilder(MapStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockPlugin = $this->getMockBuilder(ListenerInterfaceMpScriptMap::class)->getMock();

        $this->mapProvider = new ScriptMapDataProvider($this->mockMapStorage);
        $this->mapProvider->registerPlugin('test', $this->mockPlugin);
    }

    /**
     * @dataProvider methodProvider
     */
    public function testEventDispatch($method)
    {
        $map = new Map();

        $params = [
            'count' => 0,
            'time' => time(),
            'restarted' => false,
            'map' => ['uid' => 'test']
        ];

        $this->mockMapStorage
            ->expects($this->once())
            ->method('getMap')
            ->with('test')
            ->willReturn($map);

        $this->mockPlugin
            ->expects($this->once())
            ->method($method)
            ->with($params['count'], $params['time'], $params['restarted'], $map);

        $this->mapProvider->$method($params);
    }

    public function methodProvider()
    {
        return [
            ['onStartMapStart'],
            ['onStartMapEnd'],
            ['onEndMapStart'],
            ['onEndMapEnd'],
        ];
    }
}
