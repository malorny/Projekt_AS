<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Reserve;

use App\Form\ReserveLakeFormType;

use Doctrine\Persistence\ManagerRegistry;

// use App\Repository\ReserveRepository;

class LakeReservationController extends AbstractController
{
    public function reserveLake(Request $request, ManagerRegistry $doctrine)
    {
        $reservation = new Reserve();

        $reservationForm = $this->createForm(ReserveLakeFormType::class, $reservation);

        $reservationForm->handleRequest($request);

        if ($reservationForm->isSubmitted() && $reservationForm->isValid()) {
            $reservation = $reservationForm->getData();

            $reserveRepo = $doctrine->getRepository(Reserve::class);

            $reservations = $reserveRepo->anyReservation($reservation->getLakeId(), $reservation->getBeginDate(), $reservation->getEndDate());

            // skip, this lake/place is already reserved
            if ($reservations != null || (is_array($reservations) && !empty($reservations))) {
                return new Response('buu');
            }

            $reservation->setUserId($this->getUser()->getId());

            $doctrine->getManager()->persist($reservation);
            $doctrine->getManager()->flush();
        }

        return $this->render('reserve-lake.html.twig', [
            'form' => $reservationForm->createView()
        ]);
    }
}