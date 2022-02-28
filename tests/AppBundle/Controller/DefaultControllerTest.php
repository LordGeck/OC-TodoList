<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\DataFixtures\DataFixtureTestCase;

class DefaultControllerTest extends DataFixtureTestCase
{
    private $login;

    public function setUp()
    {
        parent::setUp();

        $this->login = [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'test',
        ];
    }

    public function testIndex()
    {
        $crawler = self::$client->request('GET', '/', [], [], $this->login);
        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
        $this->assertSame(
            'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !',
            $crawler->filter('h1')->text()
        );
    }
}
