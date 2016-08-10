<?php

/**
 * Created by PhpStorm.
 * User: khouloud
 * Date: 21/07/16
 * Time: 11:59 ص
 */


namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\BlogPost;

class LoadBlogPostData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $blogPostAdmin = new BlogPost();
        $blogPostAdmin->setTitle('khouloud');
        $blogPostAdmin->setBody('khouloud Belguith');
        $blogPostAdmin->setDraft('0');
        
        

        $manager->persist($blogPostAdmin);
        $manager->flush();
    }

    
    
}