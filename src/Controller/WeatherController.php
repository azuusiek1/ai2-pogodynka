<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\WeatherEntryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\LocationRepository;
use Doctrine\Persistence\ManagerRegistry;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{country?}', name: 'app_weather')]
    #[Route('/weather/{city}/{country?}', name: 'app_weather')]
    public function city(string $city, ?string $country = null, WeatherEntryRepository $weatherEntryRepository, LocationRepository $locationRepository): Response
    {
        $location = $locationRepository->findByCityAndCountry($city, $country);

        if (!$location) {
            throw $this->createNotFoundException('Location not found');
        }
    
        $weatherEntries = $weatherEntryRepository->findByLocation($location);
    
        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'weather_entries' => $weatherEntries,
        ]);
    }
    
}
