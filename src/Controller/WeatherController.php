<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Communication\Api\WeatherApiRepository;
use App\Communication\HttpClientApi;
use App\Form\WeatherType;
use Symfony\Component\Form\FormError;
use Exception;

class WeatherController extends AbstractController
{
    use HttpClientApi;

    private WeatherApiRepository $weatherApiRepository;

    public function __construct(WeatherApiRepository $weatherApiRepository)
    {
        $this->weatherApiRepository = $weatherApiRepository;
    }

    #[Route('/', name: 'weather_form')]
    public function weatherform(Request $request): Response
    {
        $form = $this->createForm(WeatherType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $localizationFormData = $form->getData();

                $this->weatherApiRepository->configApi($localizationFormData['spot'], $localizationFormData['country']);
                
                $temperature = $this->weatherApiRepository->calculateAverageTemperature();
                
                if(!is_null($temperature)){
                    $result = sprintf('Current Temperature in %s (%s) %s celcius', $localizationFormData['spot'], $localizationFormData['country'], $temperature);
                }else{
                    throw new Exception('Service not available');
                }
                
            } catch (Exception $e) {
                $dateError = new FormError($e->getMessage());
                $form->addError($dateError);
            }
        }

        return $this->renderForm('weather/localization_form.html.twig', [
            'form' => $form,
            'current_weather_show' => $result ?? ''
        ]);
    }
}
