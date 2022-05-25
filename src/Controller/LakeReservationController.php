<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Reserve;

use App\Form\ReserveLakeFormType;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function checkLake(Request $request, ManagerRegistry $doctrine)
    {
        $reserveRepo = $doctrine->getRepository(Reserve::class);

        $begin = new \DateTime();
        $end = new \DateTime();

        $begin->setDate(intval($request->get('begin_year')), intval($request->get('begin_month')), intval($request->get('begin_day')));
        $begin->setTime(intval($request->get('begin_hour')), intval($request->get('begin_minute')));

        $end->setDate(intval($request->get('end_year')), intval($request->get('end_month')), intval($request->get('end_day')));
        $end->setTime(intval($request->get('end_hour')), intval($request->get('end_minute')));

        return new JsonResponse(empty($reserveRepo->anyReservation($request->get('lake'), $begin, $end)));
    }
}