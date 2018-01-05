<?php
/**
 * File MapDataProviderTest.php
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptPodium;
use eXpansion\Framework\GameManiaplanet\DataProviders\PodiumDataProvider;

class PodiumDataProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlugin;

    /** @var PodiumDataProvider */
    protected $podiumProvider;

    protected function setUp()
    {
        parent::setUp(); 

        $this->mockPlugin = $this->getMockBuilder(ListenerInterfaceMpScriptPodium::class)->getMock();

        $this->podiumProvider = new PodiumDataProvider();
        $this->podiumProvider->registerPlugin('test', $this->mockPlugin);
    }

    /**
     * @dataProvider methodProvider
     */
    public function testEventDispatch($method)
    {
        $params = [
            'time' => time(),
        ];


        $this->mockPlugin
            ->expects($this->once())
            ->method($method)
            ->with($params['time']);

        $this->podiumProvider->$method($params);
    }

    public function methodProvider()
    {
        return [
            ['onPodiumStart'],
            ['onPodiumEnd']
        ];
    }
}
