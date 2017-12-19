<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders\Listener;

/**
 * Class RaceDataListenerInterface
 *
 * @package eXpansion\Framework\GameTrackmania\DataProviders\Listener;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
interface WaypointDataListenerInterface
{
    /**
     * @param string $login       Login of the player that crossed the CP point
     * @param int    $time        Server time when the event occured,
     * @param int    $raceTime    Total race time in milliseconds
     * @param int    $lapTime     Lap time in milliseconds
     * @param int    $stuntsScore Stunts score
     * @param int    $cpInRace    Number of checkpoints crossed since the beginning of the race
     * @param int    $cpInLap     Number of checkpoints crossed since the beginning of the lap
     * @param int[]  $curCps      Checkpoints times since the beginning of the race
     * @param int[]  $curLapCps   Checkpoints time since the beginning of the lap
     * @param string $blockId     Id of the checkpoint block
     * @param string $speed       Speed of the player in km/h
     * @param string $distance    Distance traveled by the player
     */
    public function onPlayerWayPoint(
        $login,
        $time,
        $raceTime,
        $lapTime,
        $stuntsScore,
        $cpInRace,
        $cpInLap,
        $curCps,
        $curLapCps,
        $blockId,
        $speed,
        $distance
    );
}
