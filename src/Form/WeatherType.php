<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use League\ISO3166\ISO3166;

class WeatherType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('country', ChoiceType::class, [
            'placeholder' => 'Choose an option',
            'choices' => $this->prepareCountriesList(),
            'preferred_choices' => [
                'PL'
            ]
        ])
            ->add('spot', TextType::class)
            ->add('submit', SubmitType::class, [
            'label' => 'Show',
            'attr' => [
                'class' => 'btn-success'
            ]
        ]);
    }

    private function prepareCountriesList(): array
    {
        $countries = (new ISO3166())->all();

        $result = [];
        foreach ($countries as $country) {
            $result[$country['name']] = $country['alpha2'];
        }

        return $result;
    }
}