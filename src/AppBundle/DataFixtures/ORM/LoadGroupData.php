<?php
/**
 * Created by PhpStorm.
 * User: khouloud
 * Date: 21/07/16
 * Time: 05:18 م
 */

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\UserBundle\Entity\Group;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Model\GroupableInterface;
use Symfony\Component\HttpFoundation\Tests\StringableObject;


class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface,FixtureInterface
{

    protected $name;
    protected $roles;
    public function load(ObjectManager $manager)
    {

        $groupAdmin = new Group('khouloud', $roles = array());
        $groupAdmin->setName('khouloud_Group');
        $manager->persist($groupAdmin);
        $manager->flush();

        $this->addReference('admin.group', $groupAdmin);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}

