<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Reservation;
use App\Entity\Truck;
use App\Repository\LocationRepository;
use App\Repository\ReservationRepository;
use App\Repository\TruckRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{

    #[Route('/reservations', name: 'post_reservation', methods: ["POST"])]
    public function postReservation(Request $request,
    LocationRepository $locationRepository,
    TruckRepository $truckRepository,
    ReservationRepository $reservationRepository,
    ManagerRegistry $manager): Response
    {
        function isAProperDate(DateTime $date): bool
        {
            $interval = $date->diff(new DateTime("now"));
            return $date !== false &&
            array_sum($date::getLastErrors()) === 0 &&
            $interval->days > 0 &&
            $interval->invert === 1;
        }

        function isLocationAvailable(DateTime $date, Location $location): bool
        {
            $dayOfTheWeek = $date->format('w');
            foreach($location->getDaysOfTheWeekNotAvailable() as $dayNotAvailable)
            {
                if ($dayOfTheWeek === $dayNotAvailable)
                {
                    return false;
                }
            }
            foreach($location->getDatesNotAvailable() as $dateNotAvailable)
            {
                $interval = $date->diff($dateNotAvailable);
                if ($interval->days === 0)
                {
                    return false;
                }
            }
            return true;
        }

        function hasAReservationThisWeek(ReservationRepository $reservationRepository, DateTime $date, Truck $truck): bool
        {
            $reservations = $reservationRepository->findByTruckInTheSameWeek($truck, $date);
            return count($reservations) > 0;
        }

        $data = $request->toArray();
        if (!array_key_exists("location", $data) || !array_key_exists("truck", $data) || !array_key_exists("date", $data))
        {
            return new Response("Propers location, truck and date are mandatory.", 400);
        }

        $dateFormat = "d/m/Y";
        $date = DateTime::createFromFormat($dateFormat, $data["date"]);
        if (!isAProperDate($date))
        {
            return new Response("A Proper date is mandatory. You can't make a reservation for today. Format d/m/Y, ex : 31/12/1999", 400);
        }

        $location = $locationRepository->findOneById($data["location"]);
        if (!$location)
        {
            return new Response("Location not found.", 400);
        }

        $truck = $truckRepository->findOneById($data["truck"]);
        if (!$truck)
        {
            return new Response("Truck not found.", 400);
        }

        if (!isLocationAvailable($date, $location))
        {
            return new Response("This location is not available at this date.", 400);
        }

        if (hasAReservationThisWeek($reservationRepository, $date, $truck))
        {
            return new Response("This truck already has a reservation this week.", 400);
        }

        $reservation = new Reservation();
        $reservation->setLocation($location)
        ->setDate($date)
        ->setTruck($truck);

        try {
            $entityManager = $manager->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            return $this->json("/reservations/" . $reservation->getId());
        } catch(Exception $e)
        {
            return new Response($e, 500);
        }
    }
}
