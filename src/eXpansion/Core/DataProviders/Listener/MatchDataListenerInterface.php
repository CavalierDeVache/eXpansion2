<?php
namespace eXpansion\Core\DataProviders\Listener;

use eXpansion\Core\DataProviders\PlayerDataProvider;
use eXpansion\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Interface MatchDataListenerInterface for plugins using the MatchDataProvider data provider.
 *
 * @see MatchDataProvider
 */
interface MatchDataListenerInterface
{
    public function onBeginMatch();

    public function onEndMatch();

    public function onBeginRound();

    public function onEndRound();

    public function onBeginMap(Map $map);

    public function onEndMap(Map $map);

    /**
     * Callback when player passes checkpoint.
     *
     * @param Player $player
     * @param $time
     * @param $lap
     * @param $index
     * @return mixed
     */
    public function onPlayerCheckpoint(Player $player, $time, $lap, $index);

    /**
     * Callback when player retire or finish
     *
     * @param Player $player
     * @param $time 0 if retire, > 0 if finish
     * @return mixed
     */
    public function onPlayerFinish(Player $player, $time);


}