<?php
declare(strict_types = 1);

namespace Tests\AppBundle\DataFixtures;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DataFixtureTestCase extends WebTestCase
{
    /** @var  Application $application */
    protected static $application;

    /** @var  Client $client */
    protected static $client;

    /** @var  ContainerInterface $container */
    protected static $container;

    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass(): void
    {
        self::runCommand('doctrine:database:drop --force');
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:create');
        self::runCommand('doctrine:fixtures:load --group=test --append --no-interaction');

        self::$client = static::createClient();
        self::$container = self::$client->getContainer();

        parent::setUpBeforeClass();
    }

    protected static function runCommand($command): int
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication(): Application
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    /**
     * {@inheritDoc}
     */
    public static function tearDownAfterClass(): void
    {
        self::runCommand('doctrine:database:drop --force');

        parent::tearDownAfterClass();
    }
}