<?php

namespace App\Controller;

use App\Entity\PostulatedJobs;
use App\Repository\JobsRepository;
use App\Repository\PostulatedJobsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PagesController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(JobsRepository $jobsRepository): Response
    {
        $allJobs = $jobsRepository->findAll();
        $jobs = array();
        $jobsCandidates = array();
        $user = $this->getUser();
        foreach ($allJobs as $oneJob ) {
            if ($oneJob->getPublished()) {
                array_push($jobs, $oneJob);
                array_push($jobsCandidates, [$oneJob->getTitle() => $oneJob->getCandidates()]);
            }
        }
        return $this->render('pages/home.html.twig', [
            'user' => $user,
            'jobs' => $jobs,
            'postulated' => $jobsCandidates
        ]);
    }

    /**
     * @Route("/userhome", name="userHome")
     */
    public function show_userHome(JobsRepository $jobsRepository, UsersRepository $usersRepository, PostulatedJobsRepository $postulatedJobsRepository): Response
    {
        $user = $this->getUser();
        $users = $usersRepository->findAll();
        $jobs = $jobsRepository->findAll();
        $postulatedJobsToValidate = $postulatedJobsRepository->findAll();
        $postulatedJobs =  array();
        //get all postulated jobs
        foreach($jobs as $job) {
            if($job->getCandidates()->contains($user)){
                $postulatedJobs[] = $job;
            }
        }
        return $this->render('pages/userhome.html.twig', [
            'user' => $user,
            'users' => $users,
            'jobs' => $jobs,
            'jobsPostulated' => $postulatedJobs,
            'postulatedJobsToValidate' => $postulatedJobsToValidate
        ]);
    }
}
