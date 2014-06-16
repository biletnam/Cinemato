<?php

namespace forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;

class ProduitVendeurForm extends AbstractType
{
    protected $produits;

    public function __construct($produits)
    {
        $this->produits = $produits;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $produits = array();
        foreach ($this->produits as $produit) {
            $produits[$produit->getCodeBarre()] = $produit->toString();
        }

        $builder
            ->add('produit', 'choice', array('label' => 'Produit', 'required' => true, 'choices' => $produits))
        ;
    }

    public function getName()
    {
        return 'produit_vendeur_form';
    }

    public function getDefaultOptions(array $options)
    {
        return array();
    }

    public function getExtendedType()
    {
        return 'produit_vendeur';
    }
}
