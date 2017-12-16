<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 11:02
 */

namespace Tests\eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Symfony\Component\Console\Output\NullOutput;
use Tests\eXpansion\Framework\Core\TestCore;

class ChatNotificationTest extends TestCore
{
    protected function setUp()
    {
        parent::setUp();

        $this->container->get(Console::class)->init(new NullOutput(), $this->container->get(Dispatcher::class));
    }


    public function testSendMessageLogin() {

        $colorCodes = $this->container->getParameter('expansion.config.core_chat_color_codes');
        $colorCode = $colorCodes['test'];

        $dedicatedConnection = $this->container->get('expansion.service.dedicated_connection');
        $dedicatedConnection->expects($this->once())
            ->method('chatSendServerMessage')
            ->with('$z$s' . $colorCode . 'This is a test translation : Toto', 'toto');

        $player = new Player();
        $player->merge(['language' => 'en']);
        $this->container->set(PlayerStorage::class, $this->getMockPlayerStorage($player));

        $chatNotification = $this->getChatNotificationHelper();
        $chatNotification->sendMessage('expansion_core.test_color', 'toto', ['%test%' => 'Toto']);
    }

    public function testSendMessageToAll() {

        $colorCodes = $this->container->getParameter('expansion.config.core_chat_color_codes');
        $colorCode = $colorCodes['test'];

        $translate = [
            0 => ['Lang' => 'fr', 'Text' => '$z$s' . $colorCode . 'Ceci est une trad de test : Toto'],
            1 => ['Lang' => 'de', 'Text' => '$z$s' . $colorCode . 'This is a test translation : Toto'],
        ];

        $dedicatedConnection = $this->container->get('expansion.service.dedicated_connection');
        $dedicatedConnection->expects($this->once())
            ->method('chatSendServerMessage')
            ->with(new \PHPUnit_Framework_Constraint_ArraySubset($translate), null);

        $player = new Player();
        $player->merge(['language' => 'en']);
        $this->container->set(PlayerStorage::class, $this->getMockPlayerStorage($player));

        $chatNotification = $this->getChatNotificationHelper();
        $chatNotification->sendMessage('expansion_core.test_color', null, ['%test%' => 'Toto']);
    }

    public function testSendMessageToList()
    {
        $colorCodes = $this->container->getParameter('expansion.config.core_chat_color_codes');
        $colorCode = $colorCodes['test'];

        $translate = [
            0 => ['Lang' => 'fr', 'Text' => '$z$s' . $colorCode . 'Ceci est une trad de test : Toto'],
            1 => ['Lang' => 'de', 'Text' => '$z$s' . $colorCode . 'This is a test translation : Toto'],
        ];


        $dedicatedConnection = $this->container->get('expansion.service.dedicated_connection');
        $dedicatedConnection->expects($this->once())
            ->method('chatSendServerMessage')
            ->with(new \PHPUnit_Framework_Constraint_ArraySubset($translate), 'toto1,toto2');

        $chatNotification = $this->getChatNotificationHelper();
        $chatNotification->sendMessage('expansion_core.test_color', ['toto1', 'toto2'], ['%test%' => 'Toto']);
    }

    public function testGetMessage()
    {
        $colorCodes = $this->container->getParameter('expansion.config.core_chat_color_codes');
        $colorCode = $colorCodes['test'];

        $chatNotification = $this->getChatNotificationHelper();
        $translation = $chatNotification->getMessage('expansion_core.test_color', ['%test%' => 'Toto'], 'en');

        $this->assertEquals('$z$s' . $colorCode . 'This is a test translation : Toto', $translation);

    }


    /**
     * @return ChatNotification
     */
    protected function getChatNotificationHelper()
    {
        return $this->container->get(ChatNotification::class);
    }
}
