<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\User;
use App\Entity\Reserve;
use App\Entity\Lake;

class AdminController extends AbstractController
{
    public function index(Request $request, ManagerRegistry $mr)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userRepo = $mr->getRepository(User::class);

        $users = $userRepo->findAll();

        return $this->render('admin.html.twig', [
            'users' => $users
        ]);
    }

    public function deleteReservation(Request $request, ManagerRegistry $mr, int $reservationId)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $reservationRepo = $mr->getRepository(Reserve::class);

        $reservation = $reservationRepo->findOneBy(['id' => $reservationId]);

        $mr->getManager()->remove($reservation);
        $mr->getManager()->flush();

        return $this->redirectToRoute('app_admin');
    }

    public function getAllReservations(Request $request, ManagerRegistry $mr): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userRepo = $mr->getRepository(User::class);

        $users = $userRepo->findAll();
        $reservationsRaw = $mr->getRepository(Reserve::class)->findAll();
        $lakeRepo = $mr->getRepository(Lake::class);
        $total = 0;

        $reservations = [];

        foreach ($reservationsRaw as $raw) {
            $reservation = [];

            $reservation['lakeName'] = $lakeRepo->findOneBy(['id' => $raw->getLakeId()])->getName();
            $reservation['username'] = $userRepo->findOneBy(['id' => $raw->getUserId()])->getUsername();
            $reservation['begin'] = $raw->getBeginDate()->format("d/m/Y h:i:s A");
            $reservation['end'] = $raw->getEndDate()->format("d/m/Y h:i:s A");
            $reservation['id'] = $raw->getId();
            // $reservation['reservation'] = $raw;

            $reservations['rows'][] = $reservation;

            $total++;
        }

        $reservations['total'] = $total;
        $reservations['totalNotFiltered'] = $total;

        return new JsonResponse($reservations);
    }
}