<?php

namespace App\Controller;

use App\Repository\JobsRepository;
use App\Repository\UsersRepository;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PagesController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('pages/home.html.twig', [
            'controller_name' => 'PagesController',
        ]);
    }

    /**
     * @Route("/userhome", name="userHome")
     */
    public function show_userHome(UsersRepository $usersRepository, JobsRepository $jobsRepository): Response
    {
        if($this->getUser()){
            $email = $this->getUser()->getUserIdentifier() ;
            $user= $usersRepository->findOneBy(['email' => $email]);
            $jobs = $jobsRepository->findBy(['createdBy' => $user]);
            return $this->render('pages/userhome.html.twig', [
                'user' => $user,
                'jobs' => $jobs
            ]);
        }

        return new RedirectResponse('/');
    }
}
