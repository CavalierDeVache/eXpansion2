<?php

namespace eXpansion\Bundle\Players\Plugins\Gui;

/**
 * Class IgnoreListWindow
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2018 Smile
 * @package eXpansion\Bundle\Players\Plugins\Gui
 */
class BlackListWindow extends AbstractListWindow
{
    /**
     * @inheritdoc
     */
    function getDataSet(): array
    {
        return $this->connection->getBlackList();
    }

    /**
     * @inheritdoc
     */
    function executeForPlayer($login)
    {
        $this->connection->unBlackList($login);
    }
}