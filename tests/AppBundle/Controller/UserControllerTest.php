<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\DataFixtures\DataFixtureTestCase;

class UserControllerTest extends DataFixtureTestCase
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

    public function testUserList()
    {
        $crawler = self::$client->request('GET', '/users', [], [], $this->login);
        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
        $this->assertSame(
            'Liste des utilisateurs',
            $crawler->filter('h1')->text()
        );
    }

    public function testUserCreate()
    {
        $crawler = self::$client->request('GET', '/users/create', [], [], $this->login);
        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'username',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'email@test.com',
            'user[roles]' => 'ROLE_USER',
        ]);
        self::$client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, self::$client->getResponse()->getStatusCode());

        $crawler = self::$client->followRedirect();

        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
//        dump($crawler);
        $this->assertSame(1, $crawler->filter('div.alert-success')->count());
    }

    public function testUserEdit()
    {
        $crawler = self::$client->request('GET', '/users/2/edit', [], [], $this->login);
        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'updated',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'updated@test.com',
            'user[roles]' => 'ROLE_USER',
        ]);
        self::$client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, self::$client->getResponse()->getStatusCode());

        $crawler = self::$client->followRedirect();

        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
//        dump($crawler);
        $this->assertSame(1, $crawler->filter('div.alert-success')->count());
    }
}