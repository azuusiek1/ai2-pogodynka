<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\WeatherEntry;

use App\Repository\WeatherEntryRepository;
use App\Repository\LocationRepository;

class WeatherUtil
{
    private WeatherEntryRepository $weatherEntryRepository;
    private LocationRepository $locationRepository;

    public function __construct(
        WeatherEntryRepository $weatherEntryRepository,
        LocationRepository $locationRepository
    ) {
        $this->weatherEntryRepository = $weatherEntryRepository;
        $this->locationRepository = $locationRepository;
    }

    /**
     * @throws \Exception
     * @return Location
     */
    public function getLocationById(int $locationId): Location
    {
        $location = $this->locationRepository->find($locationId);

        if (!$location) {
            throw new \Exception("Location with ID $locationId not found");
        }

        return $location;
    }

    /**
     * @return WeatherEntry[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        return $this->weatherEntryRepository->findByLocation($location);
    }

    /**
     * @return WeatherEntry[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findByCityAndCountry($city, $countryCode);
        if (!$location) {
            throw new \Exception("Location not found for city: $city and country: $countryCode");
        }

        return $this->weatherEntryRepository->findByLocation($location);
    }
}
