<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUsers extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function getOrder()
    {
        return 10;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        foreach (range(0, 19) as $i) {
            $user = new User();
            $user->setUsername('user'.$i);
            $user->setEmail('user'.$i.'@example.com');
            $user->setContract('contract'.($i % 5).'.pdf');

            $plainPassword = 'password'.$i;
            $encodedPassword = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encodedPassword);

            $this->addReference('user-'.$i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
