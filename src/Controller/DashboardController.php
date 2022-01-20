<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Reserve;
use App\Entity\Lake;

use Doctrine\Persistence\ManagerRegistry;

class DashboardController extends AbstractController
{
    public function index(Request $request, ManagerRegistry $mr)
    {
        return $this->render('dashboard.html.twig', []);
    }

    public function getReservations(Request $request, ManagerRegistry $mr): JsonResponse
    {
        $reserveRepo = $mr->getManager()->getRepository(Reserve::class);
        $lakeRepo = $mr->getManager()->getRepository(Lake::class);

        $userReservationsRaw = $reserveRepo->findBy(['user_id' => $this->getUser()->getId()]);
        $userReservations = [];
        $total = 0;

        $userReservations['rows'] = [];

        foreach ($userReservationsRaw as $raw) {
            $reservation = [];

            $reservation['id'] = $raw->getId();
            $reservation['begin'] = $raw->getBeginDate()->format("d/m/Y h:i:s A");
            $reservation['end'] = $raw->getEndDate()->format("d/m/Y h:i:s A");
            $reservation['name'] = $lakeRepo->findOneBy(['id' => $raw->getLakeId()])->getName();

            $userReservations['rows'][] = $reservation;

            $total++;
        }

        $userReservations['total'] = $total;
        $userReservations['totalNotFiltered'] = $total;

        return new JsonResponse($userReservations);
    }
}