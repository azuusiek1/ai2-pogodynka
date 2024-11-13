<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:weather-by-city',
    description: 'Displays weather forecast based on country code and city name',
)]
class WeatherLocationByCityCommand extends Command
{
    private WeatherUtil $weatherUtil;

    public function __construct(WeatherUtil $weatherUtil)
    {
        $this->weatherUtil = $weatherUtil;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('countryCode', InputArgument::REQUIRED, 'Country code (e.g., "PL" for Poland)')
            ->addArgument('city', InputArgument::REQUIRED, 'City name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = $input->getArgument('countryCode');
        $city = $input->getArgument('city');

        try {
            $weatherEntries = $this->weatherUtil->getWeatherForCountryAndCity($countryCode, $city);

            if (empty($weatherEntries)) {
                $io->warning("No weather entries found for city: $city in country: $countryCode");
            } else {
                $io->section('Weather Forecast for ' . $city . ', ' . $countryCode);
                foreach ($weatherEntries as $entry) {
                    $io->text("Date: " . $entry->getDate()->format('Y-m-d'));
                    $io->text("Temperature: " . $entry->getTemperature() . "°C");
                    $io->newLine();
                }
            }

            $io->success('Weather forecast retrieved successfully.');

        } catch (\Exception $e) {
            $io->error('Error retrieving weather data: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
