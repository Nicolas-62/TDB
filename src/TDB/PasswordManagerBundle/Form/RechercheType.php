<?php

namespace TDB\PasswordManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
	$builder
	    ->add('recherche', TextType::class)
	    ->add('envoyer', SubmitType::class);
	}

// public function configureOptions(OptionsResolver $resolver)
// {
//     $resolver->setDefaults(array(
//         'data_class' => RechercheType::class,
//     ));
// }
    public function getBlockPrefix()
    {
        return 'form_recherche';
    }
}
