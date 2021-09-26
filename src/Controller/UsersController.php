<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\JobsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="users_index", methods={"GET"})
     */
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="users_show", methods={"GET"})
     */
    public function show(Users $user): Response
    {
        if($user->getEmail() == $this->getUser()->getUserIdentifier()) {
            return $this->render('users/show.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}/edit", name="users_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Users $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $oldPassword = $user->getPassword();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->get('password')->getData() == null) {
                $user->setPassword($oldPassword);
            } else {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('userHome', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="users_delete", methods={"POST"})
     */
    public function delete(Request $request, Users $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/validate/{id}", name="users_validate", methods={"GET"})
     */
    public function validate($id,JobsRepository $jobsRepository,UsersRepository  $usersRepository): Response
    {
        $user = $this->getUser();
        $userToValidate = $usersRepository->find($id);
        $userToValidate->setValidatedBy($user);
        $userToValidate->setValidated(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userToValidate);
        $entityManager->flush();

        $jobs = $jobsRepository->findAll();
        $users = $usersRepository->findAll();
        $postulatedJobs =  array();
        return $this->render('pages/userhome.html.twig', [
            'user' => $user,
            'users' => $users,
            'jobs' => $jobs,
            'jobsPostulated' => $postulatedJobs
        ]);
        return $this->redirectToRoute('pages/userhome.html.twig');
    }

    /**
     * @Route("/reject/{id}", name="users_reject", methods={"GET"})
     */
    public function reject($id, JobsRepository $jobsRepository, UsersRepository $usersRepository): Response
    {
        $user = $this->getUser();
        $userToValidate = $usersRepository->find($id);
        $userToValidate->setValidatedBy($user);
        $userToValidate->setValidated(false);;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($userToValidate);
        $entityManager->flush();

        $jobs = $jobsRepository->findAll();
        $users = $usersRepository->findAll();
        $postulatedJobs =  array();
        return $this->render('pages/userhome.html.twig', [
            'user' => $user,
            'users' => $users,
            'jobs' => $jobs,
            'jobsPostulated' => $postulatedJobs
        ]);
        return $this->redirectToRoute('pages/userhome.html.twig');
    }
}
