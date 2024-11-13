<?php

namespace App\Controller;

use App\Repository\WeatherEntryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\LocationRepository;
use App\Service\WeatherUtil;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{country?}', name: 'app_weather')]
    public function city(string $city, ?string $country = null, WeatherUtil $util): Response
    {
        try {
            $weatherEntries = $util->getWeatherForCountryAndCity($country, $city);
            $location = $weatherEntries[0]->getLocation();
        } catch (\Exception $e) {
            throw $this->createNotFoundException('Location not found');
        }

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'weather_entries' => $weatherEntries,
        ]);
    }
    
}
