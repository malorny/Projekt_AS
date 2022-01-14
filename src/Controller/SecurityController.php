<?php

namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\User;

use App\Repository\UserRoleRepository;

use App\Form\Security\SignupFormType;

use Symfony\Component\HttpFoundation\Request;

use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function signup(Request $request, UserPasswordHasherInterface $passwordHasher, UserRoleRepository $userRoleRepo, ManagerRegistry $doctrine)
    {
        $hasAccess = $this->isGranted('ROLE_USER');

        if ($hasAccess) {
            return $this->redirectToRoute('app_dashboard');
        }

        $user = new User();

        $signupForm = $this->createForm(SignupFormType::class, $user);

        $signupForm->handleRequest($request);

        if ($signupForm->isSubmitted() && $signupForm->isValid()) {
            $user = $signupForm->getData();

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);
            $user->setRoleId($userRoleRepo->findOneBy(['name' => 'signedup'])->getId());
            $user->setRoles(['ROLE_USER']);

            $doctrine->getManager()->persist($user);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('Security/signup.html.twig', [
            'form' => $signupForm->createView()
        ]);
    }

    public function signin(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $hasAccess = $this->isGranted('ROLE_USER');

        if ($hasAccess) {
            return $this->redirectToRoute('app_dashboard');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Security/signin.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    public function signout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    public function AuthenticationSuccessEvent()
    {
        
    }
}