<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Dso;


/**
 * Class DsoFormType
 * @package AppBundle\Form
 */
class DsoFormType extends AbstractType
{

    protected $listType;

    protected $listCatalog;

    protected $listConstId;

    /**
     * DsoFormType constructor.
     * @param $listType
     * @param $listCatalog
     * @param $listConstId
     */
    public function __construct($listType, $listCatalog, $listConstId)
    {
        $this->listType = $listType;
        $this->listCatalog = $listCatalog;
        $this->listConstId = $listConstId;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        ksort($this->listType);

        $builder->add('kuzzleId', HiddenType::class);

        // desig
        $builder->add('desig', TextType::class, [
            'required' => true,
            'label' => 'desig',
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

        // Catalog
        $builder->add('catalog', ChoiceType::class, [
            'required' => true,
            'choices' => array_flip($this->listCatalog),
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

        // Order
        $builder->add('order', NumberType::class, [
            'required' => false,
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

        // Alt ??

        // type
        $builder->add('type', ChoiceType::class, [
            'required' => true,
            'choices' => array_flip($this->listType),
            'expanded' => false,
            'multiple' => false,
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

        // Magnitude
        $builder->add('mag', NumberType::class, [
            'required' => false,
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

        // Dim
        $builder->add('dim', TextType::class, [
            'required' => false,
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

        // Const
        $builder->add('constId', ChoiceType::class, [
            'required' => true,
            'expanded' => false,
            'multiple' => false,
            'choices' => array_flip($this->listConstId),
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

        // dist_al
        $builder->add('distAl', TextType::class, [
            'required' => false,
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

        // Ra
        $builder->add('ra', TextType::class, [
            'required' => false,
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

        // dec
        $builder->add('dec', TextType::class, [
            'required' => false,
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

        // AstrobinId
        $builder->add('astrobinId', IntegerType::class, [
            'required' => false,
            'attr' => [
                'class' => 'form-control'
            ]
        ]);

    }


    // Add listener for ID

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dso::class
        ]);
    }


    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'form_dso';
    }
}
