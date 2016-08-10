<?php
/**
 * Created by PhpStorm.
 * User: khouloud
 * Date: 22/07/16
 * Time: 12:23 م
 */

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\UserGroup;

class LoadUserGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userGroupAdmin = new UserGroup();
        $userGroupAdmin->setUser($this->getReference('admin.user'));
        $userGroupAdmin->setGroup($this->getReference('admin.group'));

        $manager->persist($userGroupAdmin);
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}