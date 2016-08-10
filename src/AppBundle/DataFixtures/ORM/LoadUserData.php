<?php
/**
 * Created by PhpStorm.
 * User: khouloud
 * Date: 21/07/16
 * Time: 04:11 م
 */

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface,FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('khouloud');
        $userAdmin->setPassword('khouloud');
        $userAdmin->setEmail('khouloud.belguiith@gmail.com');


        $manager->persist($userAdmin);
        $manager->flush();
        $this->addReference('user.admin', $userAdmin);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}