<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
//        $series = $serieRepository->findBy([], ['popularity' => 'DESC', 'vote' => 'DESC'], limit: 30);

        $series = $serieRepository->findBestSeries();

        return $this->render('serie/list.html.twig', [
            'series' => $series
        ]);
    }

    #[Route('/details/{id}', name: 'details')]
    public function details($id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);

        if (!$serie) {
            throw $this->createNotFoundException('Oh, Nooo !');
        }
        return $this->render('serie/details.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            $serie->setDateCreated(new \DateTime());
            $entityManager->persist($serie);
            $entityManager->flush();


            $this->addFlash('success', 'Serie added ! Good job.');

            return $this->redirectToRoute('serie_details', ['id' => $serie->getId()]);
        }
        return $this->render('serie/create.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delite(Serie $serie, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->redirectToRoute('main_home');
    }
}
