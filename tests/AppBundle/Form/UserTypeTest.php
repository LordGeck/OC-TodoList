<?php
declare(strict_types=1);

namespace Tests\AppBundle\Form;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserTypeTest extends TypeTestCase
{
    private $authorizationChecker;

    protected function setUp()
    {
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        parent::setUp();
    }

    protected function getExtensions()
    {
        $type = new UserType($this->authorizationChecker);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitValidDataWithRole()
    {
        $this->authorizationChecker->method('isGranted')->with('ROLE_ADMIN')->willReturn(true);
        $formData = [
            'username' => 'test',
            'password' => ['first' => 'pass', 'second' => 'pass'],
            'email' => 'test@test.com',
            'roles' => 'ROLE_USER',
        ];

        $model = new User();
        $expected = new User();
        $expected->setUsername('test')
            ->setPassword('pass')
            ->setEmail('test@test.com');

        $form = $this->factory->create(UserType::class, $model);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $model);
    }
}