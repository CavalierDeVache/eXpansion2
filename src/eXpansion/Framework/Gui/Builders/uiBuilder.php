<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 16.2.2018
 * Time: 21.08
 */

namespace eXpansion\Framework\Gui\Builders;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;

class uiBuilder
{
    /**
     * @var Factory
     */
    private $uiFactory;
    private $pluginClass;
    /**
     * @var ActionFactory
     */
    private $actionFactory;
    /**
     * @var ManialinkInterface
     */
    private $manialink;

    public function __construct(
        Factory $uiFactory,
        ActionFactory $actionFactory,
        ManialinkInterface $manialink,
        $pluginClass
    ) {
        $this->uiFactory = $uiFactory;
        $this->pluginClass = $pluginClass;
        $this->actionFactory = $actionFactory;
        $this->manialink = $manialink;
    }

    /**
     * @param string $xmlString
     * @return Frame
     */
    public function build($xmlString)
    {
        $xml = new \DOMDocument(1, "utf-8");
        $xml->loadXML($xmlString);

        return $this->parse($xml->documentElement);
    }

    /**
     * @param \DOMNode $node
     * @param null     $result
     * @return Frame
     */
    private function parse($node, $result = null)
    {
        if ($result === null) {
            $result = new Frame();
        }
        if ($node->nodeType == XML_TEXT_NODE) {
            /** @var DOMText $node */
            $result->setText($node->nodeValue);
        } else {

            if ($node->hasChildNodes()) {
                $children = $node->childNodes;
                for ($i = 0; $i < $children->length; $i++) {
                    $child = $children->item($i);
                    if ($child->nodeName != '#text') {
                        $aux = $this->castTagToMethod($child->nodeName);
                        $result->addChild($this->parse($child, $aux));
                    }
                }

                if ($result instanceof Label) {
                    $result->setText($node->nodeValue);
                }
                if ($result instanceof uiButton) {
                    $result->setText($node->nodeValue);
                }

                if ($node->hasAttributes()) {
                    $attributes = $node->attributes;
                    if (!is_null($attributes)) {
                        foreach ($attributes as $index => $attr) {
                            //    $result->setAttr($attr->name, $attr->value);
                            $this->parseAttributes($result, $attr);
                        }
                    }
                }
            }

            return $result;
        }
    }

    private function castTagToMethod($tag)
    {
        if (substr($tag, 0, 2) == "ui") {
            return $this->uiFactory->{"create".substr($tag, 2)}();
        } else {
            switch ($tag) {
                case "label":
                    return Label::create();
                case "quad":
                    return Quad::create();
                default:
                    return Frame::create();
            }
        }
    }


    private function parseAttributes(&$result, $attr)
    {
        switch ($attr->name) {
            case "pos":
                list($x, $y) = explode(",", $attr->value);
                $result->setPosition($x, $y);

                return;
            case "actionCallback":
                $action = $this->actionFactory->createManialinkAction($this->manialink,
                    [$this->pluginClass, $attr->value], null, true);
                $result->setAction($action);

                return;
            default:
                $method = "set".ucfirst($attr->name);
                $result->{$method}($attr->value);

                return;
        }
    }

}