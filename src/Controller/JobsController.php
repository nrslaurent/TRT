<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Entity\PostulatedJobs;
use App\Entity\Users;
use App\Form\JobsType;
use App\Repository\JobsRepository;
use App\Repository\PostulatedJobsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/jobs")
 */
class JobsController extends AbstractController
{
    /**
     * @Route("/", name="jobs_index", methods={"GET"})
     */
    public function index(JobsRepository $jobsRepository): Response
    {
        $user = $this->getUser();
        $jobs = $jobsRepository->findBy(['createdBy' => $user]);
        return $this->render('jobs/index.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * @Route("/new", name="jobs_new", methods={"GET","POST"})
     */
    public function new(Request $request, UsersRepository $usersRepository): Response
    {
        $job = new Jobs();
        $form = $this->createForm(JobsType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job->setValidated(false);
            $job->setPublished(false);
            $job->setCreatedBy($usersRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($job);
            $entityManager->flush();

            return $this->redirectToRoute('userHome', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('jobs/new.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="jobs_show", methods={"GET"})
     */
    public function show(Jobs $job): Response
    {
        return $this->render('jobs/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="jobs_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Jobs $job): Response
    {
        //check if job was created by the logged in user
        if ($job->getCreatedBy() == $this->getUser()) {
            $form = $this->createForm(JobsType::class, $job);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('jobs_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('jobs/edit.html.twig', [
                'job' => $job,
                'form' => $form,
            ]);
        }else {
            return $this->redirectToRoute('userHome');
        }
    }

    /**
     * @Route("/postulate/{id}", name="jobs_postulate")
     */
    public function postulate(Jobs $job,  JobsRepository $jobsRepository): Response
    {
        $user = $this->getUser();
        $job->addCandidate($user);
        //add job and user in PostulatedJobs table which is used to validate user's candidacy (by a consultant)
        $postulatedJob = new PostulatedJobs();
        $postulatedJob->setJob($job);
        $postulatedJob->setCandidate($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($job);
        $entityManager->persist($postulatedJob);
        $entityManager->flush();

        //get all postulated jobs
        $allJobs = $jobsRepository->findAll();
        $jobs = array();
        $jobsCandidates = array();
        foreach ($allJobs as $oneJob ) {
            if ($oneJob->getValidated()) {
                array_push($jobs, $oneJob);
                array_push($jobsCandidates, [$oneJob->getTitle() => $oneJob->getCandidates()]);
            }
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}", name="jobs_delete", methods={"POST"})
     */
    public function delete(Request $request, Jobs $job): Response
    {
        //check if job was created by the logged in user
        if ($job->getCreatedBy() == $this->getUser()) {
            if ($this->isCsrfTokenValid('delete' . $job->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($job);
                $entityManager->flush();
            }
            return $this->redirectToRoute('jobs_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/validate/{id}", name="jobs_validate", methods={"GET"})
     */
    public function validate(Jobs $job): Response
    {
        $user = $this->getUser();
        $job->setCheckedBy($user);
        $job->setValidated(true);
        $job->setPublished(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($job);
        $entityManager->flush();

        return $this->redirectToRoute('userHome');
    }

    /**
     * @Route("/reject/{id}", name="jobs_reject", methods={"GET"})
     */
    public function reject(Jobs $job): Response
    {
        $user = $this->getUser();
        $job->setCheckedBy($user);
        $job->setValidated(false);
        $job->setPublished(false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($job);
        $entityManager->flush();

        return $this->redirectToRoute('userHome');
    }

    /**
     * @Route("/PostulatedJob/validate/{id}", name="PostulatedJob_validate", methods={"GET"})
     */
    public function PostulatedJob_validate(PostulatedJobs $postulatedJobs): Response
    {
        $user = $this->getUser();
        $postulatedJobs->setCheckedBy($user);
        $postulatedJobs->setValidated(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($postulatedJobs);
        $entityManager->flush();

        return $this->redirectToRoute('userHome');
    }

    /**
     * @Route("/PostulatedJob/reject/{id}", name="PostulatedJob_reject", methods={"GET"})
     */
    public function PostulatedJob_reject(PostulatedJobs $postulatedJobs): Response
    {
        $user = $this->getUser();
        $postulatedJobs->setCheckedBy($user);
        $postulatedJobs->setValidated(false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($postulatedJobs);
        $entityManager->flush();

        return $this->redirectToRoute('userHome');
    }
}
