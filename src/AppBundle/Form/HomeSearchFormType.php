<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Blank;

/**
 * Class HomeSearchFormType
 * @package AppBundle\Form
 */
class HomeSearchFormType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('search', TextType::class, [
            [
                'label' => '',
                'attr' => [
                    'placeholder' => 'home.search.placeholder',
                    'class' => ''
                ]
            ]
        ]);

        $builder->add('honeypot', HiddenType::class, [
            'constraints' => [
                new Blank(['message' => 'form.constraint.empty'])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'home_search';
    }
}
