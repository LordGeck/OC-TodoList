<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\DataFixtures\DataFixtureTestCase;

class TaskControllerTest extends DataFixtureTestCase
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

    public function testTaskList()
    {
        $crawler = self::$client->request('GET', '/tasks', [], [], $this->login);
        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
        $this->assertSame(
            'Créer une tâche',
            $crawler->filter('a.btn-info')->text()
        );
    }

    public function testTaskCreate()
    {
        $crawler = self::$client->request('GET', '/tasks/create', [], [], $this->login);
        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'title',
            'task[content]' => 'content',
        ]);
        self::$client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, self::$client->getResponse()->getStatusCode());

        $crawler = self::$client->followRedirect();

        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success')->count());
    }

    public function testTaskEdit()
    {
        $crawler = self::$client->request('GET', '/tasks/1/edit', [], [], $this->login);
        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'title',
            'task[content]' => 'content',
        ]);
        self::$client->submit($form);
        $this->assertSame(Response::HTTP_FOUND, self::$client->getResponse()->getStatusCode());

        $crawler = self::$client->followRedirect();

        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success')->count());
    }

    public function testTaskToggle()
    {
        self::$client->request('GET', '/tasks/1/toggle', [], [], $this->login);
        $this->assertSame(Response::HTTP_FOUND, self::$client->getResponse()->getStatusCode());

        $crawler = self::$client->followRedirect();

        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success')->count());
    }

    public function testTaskDelete()
    {
        self::$client->request('GET', '/tasks/1/delete', [], [], $this->login);
        $this->assertSame(Response::HTTP_FOUND, self::$client->getResponse()->getStatusCode());

        $crawler = self::$client->followRedirect();

        $this->assertSame(Response::HTTP_OK, self::$client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert-success')->count());
    }
}