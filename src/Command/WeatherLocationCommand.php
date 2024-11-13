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
    name: 'weather:location',
    description: 'Displays weather forecast for a given location ID',
)]
class WeatherLocationCommand extends Command
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
            ->addArgument('locationId', InputArgument::REQUIRED, 'ID of the location')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $locationId = $input->getArgument('locationId');

        try {
            $location = $this->weatherUtil->getLocationById($locationId);
            $weatherEntries = $this->weatherUtil->getWeatherForLocation($location);

            if (empty($weatherEntries)) {
                $io->warning("No weather entries found for location ID: $locationId");
            } else {
                $io->section('Weather Forecast for ' . $location->getCity() . ', ' . $location->getCountry());
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
