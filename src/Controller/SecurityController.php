<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 13/06/2018
 * Time: 15:13
 */

namespace App\Controller;

use App\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{

    /**
     * @Route("/login", name="security_login");
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class,[
            '_username' => $lastUsername
        ]);

        return $this->render(
            'security/login.html.twig',
            array(
            'form' => $form->createView(),
            'error'         => $error,
            )
        );
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('this should not be reached');
    }
}