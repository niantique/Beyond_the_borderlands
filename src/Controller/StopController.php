<?php

namespace App\Controller;

use App\Entity\Stop;
use App\Entity\Trip;
use App\Form\StopType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class StopController extends AbstractController
{
    #[Route('/trip/{id}/stop', name: 'stop')]
    public function addStop(Trip $trip, Request $request, EntityManagerInterface $em): Response
    {
        $stop = new Stop();
        $stop->setTrip($trip);

        $form = $this->createForm(StopType::class, $stop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            $imageUrl = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $uploadDir = $this->getParameter('stops_images_directory');
                $imageFile->move($uploadDir,$newFilename);
                $stop->setImage('/uploads/stops/' . $newFilename);
            } elseif ($imageUrl) {
                $stop->setImage($imageUrl);
            }

            $em->persist($stop);
            $em->flush();

            $this->addFlash('success', 'Stop added successfully.');

            return $this->redirectToRoute('trip', ['id' => $trip->getId()]);
        }

        return $this->render('stop/index.html.twig', [
            'form' => $form->createView(),
            'trip' => $trip,
        ]);
    }
}
