<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 13:06
 */

namespace eXpansion\Framework\GameManiaplanet\DataProviders\Listener;

use eXpansion\Framework\GameManiaplanet\DataProviders\ChatDataProvider;
use eXpansion\Framework\Core\Storage\Data\Player;

/**
 * Interface ChatDataListenerInterface for plugins using the ChatDataProvider data provider. *
 * @see ChatDataProvider
 *
 * @package eXpansion\Framework\GameManiaplanet\DataProviders\Listener
 */
interface ListenerInterfaceMpLegacyChat
{
    /**
     * Called when a player chats.
     *
     * @param Player $player
     * @param $text
     *
     * @return void
     */
    public function onPlayerChat(Player $player, $text);
}
