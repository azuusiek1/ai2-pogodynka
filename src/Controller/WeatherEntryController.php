<?php

namespace App\Controller;

use App\Entity\WeatherEntry;
use App\Form\WeatherEntryType;
use App\Repository\WeatherEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/weatherentry')]
class WeatherEntryController extends AbstractController
{
    #[Route('/', name: 'app_weather_entry_index', methods: ['GET'])]
    public function index(WeatherEntryRepository $weatherEntryRepository): Response
    {
        return $this->render('weather_entry/index.html.twig', [
            'weather_entries' => $weatherEntryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_weather_entry_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $weatherEntry = new WeatherEntry();
        $form = $this->createForm(WeatherEntryType::class, $weatherEntry, [
            'validation_groups' => 'create'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($weatherEntry);
            $entityManager->flush();

            return $this->redirectToRoute('app_weather_entry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weather_entry/new.html.twig', [
            'weather_entry' => $weatherEntry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_entry_show', methods: ['GET'])]
    public function show(WeatherEntry $weatherEntry): Response
    {
        return $this->render('weather_entry/show.html.twig', [
            'weather_entry' => $weatherEntry,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_weather_entry_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WeatherEntry $weatherEntry, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WeatherEntryType::class, $weatherEntry, [
            'validation_groups' => 'edit'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_weather_entry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weather_entry/edit.html.twig', [
            'weather_entry' => $weatherEntry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_entry_delete', methods: ['POST'])]
    public function delete(Request $request, WeatherEntry $weatherEntry, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weatherEntry->getId(), $request->request->get('_token'))) {
            $entityManager->remove($weatherEntry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_weather_entry_index', [], Response::HTTP_SEE_OTHER);
    }
}
