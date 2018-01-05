<?php
/**
 * File MapDataProviderTest.php
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMatch;
use eXpansion\Framework\GameManiaplanet\DataProviders\ScriptMatchDataProvider;

class ScriptMatchDataProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlugin;

    /** @var ScriptMatchDataProvider */
    protected $matchProvider;

    protected function setUp()
    {
        parent::setUp(); 

        $this->mockPlugin = $this->getMockBuilder(ListenerInterfaceMpScriptMatch::class)->getMock();

        $this->matchProvider = new ScriptMatchDataProvider();
        $this->matchProvider->registerPlugin('test', $this->mockPlugin);
    }

    /**
     * @dataProvider methodProvider
     */
    public function testEventDispatch($method)
    {
        $params = [
            'count' => 0,
            'time' => time(),
        ];


        $this->mockPlugin
            ->expects($this->once())
            ->method($method)
            ->with($params['count'], $params['time']);

        $this->matchProvider->$method($params);
    }

    public function methodProvider()
    {
        return [
            ['onStartMatchStart'],
            ['onStartMatchEnd'],
            ['onEndMatchStart'],
            ['onEndMatchEnd'],
            ['onStartRoundStart'],
            ['onStartRoundEnd'],
            ['onEndRoundStart'],
            ['onEndRoundEnd'],
            ['onStartTurnStart'],
            ['onStartTurnEnd'],
            ['onEndTurnStart'],
            ['onEndTurnEnd'],
        ];
    }
}
