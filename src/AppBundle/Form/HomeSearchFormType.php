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
        $builder->add('terms', TextType::class,
            [
                'label' => '',
                'attr' => [
                    'placeholder' => 'home.search.placeholder',
                    'class' => 'form-control form-control-lg typeahead',
                    'autocomplete' => 'off'
                ]
            ]
        );

        $builder->add('honeypot', HiddenType::class, [
            'constraints' => [
                new Blank(['message' => 'form.constraint.empty'])
            ]
        ]);
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true
        ]);
    }


    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'home_search';
    }
}
