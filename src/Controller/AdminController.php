<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;

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
        $reservationsRaw = $mr->getRepository(Reserve::class)->findAll();
        $lakeRepo = $mr->getRepository(Lake::class);

        $reservations = [];

        foreach ($reservationsRaw as $raw) {
            $reservation = [];

            $reservation['lakeName'] = $lakeRepo->findOneBy(['id' => $raw->getLakeId()])->getName();
            $reservation['username'] = $userRepo->findOneBy(['id' => $raw->getUserId()])->getUsername();
            $reservation['reservation'] = $raw;

            $reservations[] = $reservation;
        }

        return $this->render('admin.html.twig', [
            'users' => $users,
            'reservations' => $reservations
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
}