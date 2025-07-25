<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class TripController extends AbstractController
{
    #[Route('/trip', name: 'trip')]
    public function index(TripRepository $tripRepository): Response
    {
        $trips = $tripRepository->findBy([], ['id' => 'DESC']);

        return $this->render('trip/index.html.twig', [
            'trips' => $trips,
        ]);
    }

    #[Route('/trip/read/{id}', name: 'trip_read')]
    public function read(Trip $trip): Response
    {
        return $this->render('trip/read.html.twig', [
            'trip' => $trip
        ]);
    }

    #[Route('/trip/new', name: 'trip_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $trip = new Trip();
        $trip->setUser($this->getUser());
        $form = $this->createForm(TripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($trip);
            $em->flush();

            $this->addFlash('success', 'Trip created successfully !');

            return $this->redirectToRoute('trip');
        }

        return $this->render('trip/new.html.twig', [
            'tripForm' => $form->createView(),
        ]);
    }
}
