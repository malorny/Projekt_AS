<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\Reserve;
use App\Entity\Lake;

use Doctrine\Persistence\ManagerRegistry;

class DashboardController extends AbstractController
{
    public function index(Request $request, ManagerRegistry $mr)
    {
        $reserveRepo = $mr->getManager()->getRepository(Reserve::class);
        $lakeRepo = $mr->getManager()->getRepository(Lake::class);

        $userReservationsRaw = $reserveRepo->findBy(['user_id' => $this->getUser()->getId()]);
        $userReservations = [];

        foreach ($userReservationsRaw as $raw) {
            $reservation = [];

            $reservation['begin'] = $raw->getBeginDate();
            $reservation['end'] = $raw->getEndDate();
            $reservation['name'] = $lakeRepo->findOneBy(['id' => $raw->getLakeId()])->getName();

            $userReservations[] = $reservation;
        }

        // var_dump($userReservations);

        return $this->render('dashboard.html.twig', [
            'userReservations' => $userReservations
        ]);
    }
}