<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 25.2.2018
 * Time: 19.52
 */

namespace eXpansion\Framework\GameCurrencyBundle\Plugins\Gui;


use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\GameCurrencyBundle\Services\GameCurrencyService;

class BillWindow extends WindowFactory
{
    /**
     * @var GameCurrencyService
     */
    private $currencyService;
    /**
     * @var GameDataStorage
     */
    private $gameDataStorage;
    private $recipient = "";
    private $amount = "";


    /**
     * BillWindow constructor.
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param null                 $posX
     * @param null                 $posY
     * @param WindowFactoryContext $context
     * @param GameCurrencyService  $currencyService
     * @param GameDataStorage      $gameDataStorage
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        WindowFactoryContext $context,
        GameCurrencyService $currencyService,
        GameDataStorage $gameDataStorage
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->currencyService = $currencyService;
        $this->gameDataStorage = $gameDataStorage;
    }

    public function setDetails($login, $amount)
    {
        $this->recipient = $login;
        $this->amount = $amount;
    }

    protected function createContent(ManialinkInterface $manialink)
    {

        $column1 = $this->uiFactory->createLayoutRow(0, 0, [], 1);
        $column1->addChildren([
            $this->uiFactory->createLabel("Recipient"),
            $this->uiFactory->createInput("login")->setDefault($this->recipient),
            $this->uiFactory->createLabel("Amount"),
            $this->uiFactory->createInput("amount")->setDefault($this->amount),
        ]);


        $actions = $this->uiFactory->createLayoutLine(0, 0, [], 3);
        $actions->addChildren([
            $this->uiFactory->createButton("Send")->setAction(
                $this->actionFactory->createManialinkAction($manialink, [$this, "callbackSend"], null)
            ),
            $this->uiFactory->createButton("Cancel")->setAction(
                $this->actionFactory->createManialinkAction($manialink, [$this, "callbackCancel"], null)
            ),
        ]);


        $column2 = $this->uiFactory->createLayoutRow(0, 0, [], 1);
        $column2->addChildren([
            $this->uiFactory->createLabel("Message"),
            $this->uiFactory->createTextbox("message", "...", 3)
                ->setWidth(50),
            $actions,
        ]);

        $manialink->addChild($this->uiFactory->createLayoutLine(0, 0, [$column1, $column2], 2));
    }

    /** @param ManialinkInterface|Window $manialink */
    public function callbackSend($manialink, $login, $entries, $args)
    {
        if (!is_numeric($entries['amount'])) {
            $this->setBusy($manialink, "Amount is not integer.");

            return;
        }
        $serverLogin = $this->gameDataStorage->getSystemInfo()->serverLogin;
        $bill = $this->currencyService->createBill(
            $serverLogin,
            $entries['amount'],
            $entries['login'],
            $entries['message']
        );

        $this->setBusy($manialink, "Processing...");

        if ($bill == false) {
            $this->closeManialink($manialink);
        }

        $this->currencyService->sendBill(
            $bill,
            function () use ($manialink) {
                $this->closeManialink($manialink);
            },
            function () use ($manialink) {
                $this->closeManialink($manialink);
            }
        );
    }

    /** @param ManialinkInterface|Window $manialink */
    public function callbackCancel($manialink, $login, $entries, $args)
    {
        $this->closeManialink($manialink);
    }

}