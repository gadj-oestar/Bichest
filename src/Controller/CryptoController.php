<?php

namespace App\Controller;

use App\Entity\Crypto;
use App\Form\CryptoType;
use App\Repository\CryptoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/crypto')]
class CryptoController extends AbstractController
{
    #[Route('/', name: 'app_crypto_index', methods: ['GET'])]
    public function index(CryptoRepository $cryptoRepository): Response
    {
        return $this->render('crypto/index.html.twig', [
            'cryptos' => $cryptoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_crypto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $crypto = new Crypto();
        $form = $this->createForm(CryptoType::class, $crypto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($crypto);
            $entityManager->flush();

            return $this->redirectToRoute('app_crypto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crypto/new.html.twig', [
            'crypto' => $crypto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crypto_show', methods: ['GET'])]
    public function show(Crypto $crypto): Response
    {
        return $this->render('crypto/show.html.twig', [
            'crypto' => $crypto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_crypto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Crypto $crypto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CryptoType::class, $crypto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_crypto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crypto/edit.html.twig', [
            'crypto' => $crypto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_crypto_delete', methods: ['POST'])]
    public function delete(Request $request, Crypto $crypto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$crypto->getId(), $request->request->get('_token'))) {
            $entityManager->remove($crypto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_crypto_index', [], Response::HTTP_SEE_OTHER);
    }
}

