<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Service\WeatherUtil;

class WeatherApiController extends AbstractController
{
    private WeatherUtil $weatherUtil;

    public function __construct(WeatherUtil $weatherUtil)
    {
        $this->weatherUtil = $weatherUtil;
    }

    #[Route('/weather/v1/api', name: 'app_weather_api')]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false
    ): Response {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($format === 'csv') {
            if($twig) {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            } else {
                $csvData = array_map(fn($m) => sprintf(
                    '%s,%s,%s,%.2f,%.2f',
                    $city,
                    $country,
                    $m->getDate()->format('Y-m-d'),
                    $m->getTemperature(),
                    $m->getFahrenheit()
                ), $measurements);

                array_unshift($csvData, 'city,country,date,celsius,farenheit');
                $csvOutput = implode("\n", $csvData);

                $response = new Response($csvOutput);
                return $response;
            }
        }

        $formattedMeasurements = array_map(fn($m) => [
            'date' => $m->getDate()->format('Y-m-d'),
            'temperature' => $m->getTemperature(),
            'fahrenheit' => $m->getFahrenheit(),
        ], $measurements);
        if($twig) {
            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $formattedMeasurements,
            ]);
        } else {
            return $this->json([
                'country' => $country,
                'city' => $city,
                'measurements' => $formattedMeasurements,
            ]);
        }
    }
}
