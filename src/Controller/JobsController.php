<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Form\JobsType;
use App\Repository\JobsRepository;
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

            return $this->redirectToRoute('jobs_index', [], Response::HTTP_SEE_OTHER);
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
        //check if job was created by the logged in user
        if ($job->getCreatedBy() == $this->getUser()) {
            return $this->render('jobs/show.html.twig', [
                'job' => $job,
            ]);
        }else {
            return $this->redirectToRoute('home');
        }
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
            return $this->redirectToRoute('home');
        }
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
}
