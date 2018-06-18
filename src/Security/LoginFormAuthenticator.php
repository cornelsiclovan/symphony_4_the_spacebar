<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 14/06/2018
 * Time: 10:13
 */

namespace App\Security;


use App\Entity\User;
use App\Form\LoginForm;
use App\Service\HashPasswordListener;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{

    use TargetPathTrait;

    private $formFactory;
    private $em;
    private $router;
    private $passwordEncoder;
    private $hashPasswordListener;

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $em, RouterInterface $router, UserPasswordEncoderInterface $passwordEncoder, HashPasswordListener $hashPasswordListener)
    {
       $this->formFactory = $formFactory;
       $this->em = $em;
       $this->router = $router;
       $this->passwordEncoder = $passwordEncoder;
       $this->hashPasswordListener = $hashPasswordListener;
    }

    public function supports(Request $request)
    {
        // if this returns true, then getCredentials() is called
        return $request->getPathInfo() == '/login' && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {

        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);
        $data = $form->getData();

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['_username']
        );

        return $data;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');
    }


    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['_username'];
        return $this->em->getRepository(User::class)
            ->findOneBy(['email' => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];

       // dump($password, $user); die;

        if($this->passwordEncoder->isPasswordValid($user, $password)){
            return true;
        }
        return false;
    }




    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);

        if (!$targetPath) {
            $targetPath = $this->router->generate('app_homepage');
        }

        return new RedirectResponse($targetPath);

    }

}