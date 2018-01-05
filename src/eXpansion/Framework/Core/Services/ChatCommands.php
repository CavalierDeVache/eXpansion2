<?php

namespace eXpansion\Framework\Core\Services;

use eXpansion\Framework\Core\Exceptions\ChatCommand\CommandExistException;
use eXpansion\Framework\Core\Model\ChatCommand\ChatCommandInterface;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ChatCommandInterface as ChatCommandPluginInterface;

/**
 * Class ChatCommands store all currently active chat commands.
 *
 * @package eXpansion\Framework\Core\Services;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ChatCommands
{
    /** @var ChatCommandInterface[] */
    protected $commands = array();

    /** @var ChatCommandInterface[][] */
    protected $commandPlugin;

    /** @var int */
    protected $depth = 3;

    /**
     * ChatCommands constructor.
     *
     * @param int $depth
     */
    public function __construct($depth)
    {
        $this->depth = $depth;
    }


    /**
     * Registers all chat commands of a plugin.
     *
     * @param string                     $pluginId
     * @param ChatCommandPluginInterface $pluginService
     *
     * @throws CommandExistException
     */
    public function registerPlugin($pluginId, ChatCommandPluginInterface $pluginService)
    {
        $commands = $pluginService->getChatCommands();

        foreach ($commands as $command) {
            $this->addCommand($pluginId, $command->getCommand(), $command);

            foreach ($command->getAliases() as $alias) {
                $this->addCommand($pluginId, $alias, $command);
            }
        }
    }

    /**
     * Remove all chat commands registered for a plugin.
     *
     * @param $pluginId
     */
    public function deletePlugin($pluginId)
    {
        if (!isset($this->commandPlugin[$pluginId])) {
            return;
        }

        foreach ($this->commandPlugin[$pluginId] as $cmdTxt => $command) {
            unset($this->commands[$cmdTxt]);
        }
        unset($this->commandPlugin[$pluginId]);
    }

    /**
     * Get a chat command.
     *
     * @param string[] $cmdAndArgs
     * @return array
     */
    public function getChatCommand($cmdAndArgs)
    {
        return $this->findChatCommand($cmdAndArgs, $this->depth);
    }

    /**
     * Get list of all chat commands.
     *
     * return array
     */
    public function getChatCommands()
    {
        $chatCommands = [];
        foreach ($this->commands as $chatCommand => $data) {
            $chatCommands[$data->getCommand()] = clone $data;
        }

        return $chatCommands;
    }

    /**
     * Find a chat command.
     *
     * @param string[] $cmdAndArgs
     * @param integer  $depth
     *
     * @return mixed
     */
    protected function findChatCommand($cmdAndArgs, $depth, $orig = null)
    {
        if ($depth == 0) {
            return [null, []];
        }

        if ($orig == null) {
            $orig = $cmdAndArgs;
        }

        $cmdAndArgs = array_splice($cmdAndArgs, 0, $depth);
        $parameters = array_diff($orig, $cmdAndArgs);
        $command = implode(' ', $cmdAndArgs);

        return isset($this->commands[$command])
            ? [$this->commands[$command], $parameters]
            : $this->findChatCommand($cmdAndArgs, $depth - 1, $orig);
    }

    /**
     * Register a command.
     *
     * @param string               $pluginId
     * @param string               $cmdTxt
     * @param ChatCommandInterface $command
     *
     * @throws CommandExistException
     */
    protected function addCommand($pluginId, $cmdTxt, ChatCommandInterface $command)
    {
        if (isset($this->commands[$cmdTxt])) {
            throw new CommandExistException(
                "$pluginId tries to register command '$cmdTxt' already registered"
            );
        }

        $this->commands[$cmdTxt] = $command;
        $this->commandPlugin[$pluginId][$cmdTxt] = $command;
    }
}
