<?php


namespace Tests\eXpansion\Framework\Core\Model\UserGoups;


use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use Tests\eXpansion\Framework\Core\TestCore;


class GroupTest extends TestCore
{

    public function testPersistentGroup()
    {
        $group = new Group("test", $this->container->get(Dispatcher::class));

        $group->addLogin('l1');
        $group->addLogin('l2');

        $this->assertEquals('test', $group->getName());
        $this->assertEquals(['l1', 'l2'], $group->getLogins());
        $this->assertTrue($group->hasLogin('l1'));
        $this->assertTrue($group->isPersistent());

        $group->removeLogin('l1');
        $this->assertFalse($group->hasLogin('l1'));
    }

    public function testNonPersistentGroup()
    {
        $group = new Group(null, $this->container->get(Dispatcher::class));
        $this->assertFalse($group->isPersistent());
    }
}
