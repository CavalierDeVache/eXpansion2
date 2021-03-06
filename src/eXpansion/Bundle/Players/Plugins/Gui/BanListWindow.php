<?php

namespace eXpansion\Bundle\Players\Plugins\Gui;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;

/**
 * Class IgnoreListWindow
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2018 Smile
 * @package eXpansion\Bundle\Players\Plugins\Gui
 */
class BanListWindow extends AbstractListWindow
{
    /**
     * @inheritdoc
     */
    function getDataSet(): array
    {
        return $this->connection->getBanList();
    }

    /**
     * @inheritdoc
     */
    function executeForPlayer($login)
    {
        $this->connection->unBan($login);
    }
}