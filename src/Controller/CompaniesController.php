<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Entity\Users;
use App\Form\CompaniesType;
use App\Repository\CompaniesRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/companies")
 */
class CompaniesController extends AbstractController
{
    /**
     * @Route("/", name="companies_index", methods={"GET"})
     */
    public function index(CompaniesRepository $companiesRepository): Response
    {
        return $this->render('companies/index.html.twig', [
            'companies' => $companiesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="companies_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $company = new Companies();
        $user = $this->getUser();
        $user->setCompany($company);
        $form = $this->createForm(CompaniesType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($company);
            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('userHome', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('companies/new.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="companies_show", methods={"GET"})
     */
    public function show(Companies $company, UsersRepository $usersRepository): Response
    {
        //check if it is the user's company
        if ($company->getUsers()->contains($this->getUser())) {
            return $this->render('companies/show.html.twig', [
                'company' => $company,
            ]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}/edit", name="companies_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Companies $company): Response
    {
        //check if it is the user's company
        if ($company->getUsers()->contains($this->getUser())) {
            $form = $this->createForm(CompaniesType::class, $company);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('userHome', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('companies/edit.html.twig', [
                'company' => $company,
                'form' => $form,
            ]);
        } else {
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}", name="companies_delete", methods={"POST"})
     */
    public function delete(Request $request, Companies $company): Response
    {
        //check if it is the user's company
        if ($company->getUsers()->contains($this->getUser())) {
            //check if he is the only user of this company
            if ($company->getUsers()->containsKey(1)) {
                return new Response('Cette société contient d\'autres recruteurs et ne peut pas être supprimée !');
            } else {
                if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
                    $company->removeUser($this->getUser());
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($company);
                    $entityManager->flush();
                }
                return $this->redirectToRoute('companies_index', [], Response::HTTP_SEE_OTHER);
            }

        } else {
            return $this->redirectToRoute('home');
        }
    }
}
