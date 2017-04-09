<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Class Restart
 *
 * @package eXpansion\Bundle\AdminChat\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class Next extends AbstractConnectionCommand
{
    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $this->chatNotification->sendMessage('expansion_admin_chat.next', null,['%nickname%' => $nickName]);
        $this->connection->nextMap();
    }
}
