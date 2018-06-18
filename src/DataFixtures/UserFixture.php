<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 13/06/2018
 * Time: 14:39
 */

namespace App\DataFixtures;


use App\Service\HashPasswordListener;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends BaseFixture
{
    private $hashPasswordListener;

    public function __construct(HashPasswordListener $hashPasswordListener)
    {
        return $this->hashPasswordListener = $hashPasswordListener;
    }


    protected function loadData(ObjectManager $em)
    {
        $this->createMany(User::class, 20, function(User $user){
            $user->setEmail($this->faker->email);
            $user->setPlainPassword('123');
            $this->hashPasswordListener->encodePassword($user);
        });

        $em->flush();
    }
}