<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ListOrderFormType
 * @package AppBundle\Form
 */
class ListOrderFormType extends AbstractType
{
    private $listOrder;

    /**
     * ListOrderFormType constructor.
     * @param $listOrder
     */
    public function __construct($listOrder)
    {
        $this->listOrder = $listOrder;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sort', ChoiceType::class, [
            'choices' => $this->listOrder,
            'label' => 'select.order.label',
            'placeholder' => 'select.order.placeholder',
            'expanded' => false,
            'multiple' => false,
            'attr' => [

                'class' => 'form-control'
            ],
            'data' => $options['selectedOrder']
        ]);
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'selectedOrder' => null
        ]);
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'list_order';
    }
}
