<?php

namespace AppBundle\Form;

use KleeGroup\GoogleReCaptchaBundle\Form\Type\ReCaptchaType;
use KleeGroup\GoogleReCaptchaBundle\Validator\Constraints\ReCaptcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ContactFormType
 * @package AppBundle\Form
 */
class ContactFormType extends AbstractType
{

    private $locale;

    /**
     * ContactFormType constructor.
     * @param $locale
     */
    public function __construct($locale = 'en')
    {
        $this->locale = $locale;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        dump($this->locale);

        $builder->add('firstname', TextType::class, [
            'label' => 'contact.form.firstname',
            'attr' => [
                'placeholder' => 'contact.placeholder.firstname',
                'class' => 'form-control'
            ],
            'constraints' => [
                new NotBlank(['message' => 'contact.err.empty'])
            ]
        ]);

        $builder->add('lastname', TextType::class, [
            'label' => 'contact.form.lastname',
            'attr' => [
                'placeholder' => 'contact.placeholder.lastname',
                'class' => 'form-control'
            ],
            'constraints' => [
                new NotBlank(['message' => 'contact.err.empty'])
            ]
        ]);

        $builder->add('email', RepeatedType::class, [
            'type' => EmailType::class,
            'first_options' => [
                'label' => 'contact.form.email',
                'attr' => [
                    'placeholder' => 'contact.placeholder.email',
                    'class' => 'form-control'
                ],
            ],
            'second_options' => [
                'label' => 'contact.form.confirm_email',
                'attr' => [
                    'placeholder' => 'contact.placeholder.email',
                    'class' => 'form-control'
                ],
            ],
            'constraints' => [
                new Email(['message' => 'contact.err.email'])
            ]
        ]);

        $builder->add('country', CountryType::class, [
            'label' => 'contact.form.country',
            'preferred_choices' => [strtoupper($this->locale)],
            'required' => false,
            'attr' => [
                'placeholder' => 'contact.placeholder.country',
                'class' => 'form-control'
            ]
        ]);

        $builder->add('reasons', ChoiceType::class, [
            'label' => 'contact.form.reasons',
            'choices' => [

            ],
            'attr' => [
                'class' => 'form-control'
            ],
        ]);

        $builder->add('message', TextareaType::class, [
            'label' => 'contact.form.message',
            'attr' => [
                'class' => 'form-control',
                'rows' => 8,
            ],
        ]);

        $builder->add('recaptcha', ReCaptchaType::class, [
            'mapped' => false,
            'constraints' => [
                new ReCaptcha()
            ]
        ]);

        $builder->add('honeypot', HiddenType::class, [
            'mapped' => false,
            'constraints' => [
                new Blank()
            ]
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
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
        return 'form_contact';
    }
}
