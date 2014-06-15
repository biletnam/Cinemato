<?php

namespace forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;

class ProduitBoissonForm extends AbstractType
{
    protected $isUpdateForm;

    public function __construct($isUpdateForm = false)
    {
        $this->isUpdateForm = $isUpdateForm;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$this->isUpdateForm) {
            $builder->add('codeBarre', 'integer', array('label' => 'CodeBarre', 'required' => true));
        }

        $builder
            ->add('nomDeProduit', 'text', array('label' => 'Nom', 'required' => true))
            ->add('prix', 'number', array('label' => 'Prix', 'precision' => 2, 'grouping' => NumberToLocalizedStringTransformer::ROUND_HALF_EVEN, 'required' => true))
        ;
    }

    public function getName()
    {
        return 'produit_boisson_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array();
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'model\Entite\ProduitBoisson'
        ));
    }

    public function getExtendedType()
    {
        return 'produit_boisson';
    }
}
