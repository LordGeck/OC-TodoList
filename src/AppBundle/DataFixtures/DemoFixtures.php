<?php
declare(strict_types = 1);

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DemoFixtures extends Fixture implements ContainerAwareInterface, FixtureGroupInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $password = $this->container->get('security.password_encoder')->encodePassword($admin, 'password');
        $admin->setUsername('DemoAdmin')
            ->setPassword($password)
            ->setEmail('demo.admin@email.com')
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $user = new User();
        $password = $this->container->get('security.password_encoder')->encodePassword($user, 'password');
        $user->setUsername('DemoUser')
            ->setPassword($password)
            ->setEmail('demo.user@email.com')
            ->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $task = new Task();
        $task->setTitle('title')
            ->setContent('content')
            ->setUser($user);
        $manager->persist($task);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['demo'];
    }
}