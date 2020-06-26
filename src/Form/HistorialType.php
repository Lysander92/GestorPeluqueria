<?php

namespace App\Form;

use App\Entity\Historial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Entity\Cliente;
use Doctrine\ORM\EntityRepository;

class HistorialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', DateType::class, [
                'widget' => 'single_text',
                
                'format' => 'dd-MM-yyyy',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker', 'autocomplete' => 'off'],
                

                'data' => new \DateTime()
            ])
            ->add('Cliente', EntityType::class, [
                // looks for choices from this entity
                'class' => Cliente::class,
                
                'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->where('c.eliminado = false')
                            ->orderBy('c.apellido', 'ASC');
                    },

                // uses the User.username property as the visible option string
                //'choice_label' => 'apellido',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('factura', HiddenType::class, [
                'empty_data' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Historial::class,
        ]);
    }
}
