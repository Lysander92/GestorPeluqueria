<?php

namespace App\Form;

use App\Entity\RenglonHistorial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use App\Entity\Producto;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class RenglonHistorialType extends AbstractType
{
    private $em;
    
    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     * 
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('detalle', TextType::class, ['empty_data' => ''])
            ->add('cantidad', NumberType::class)
            ->add('precio', NumberType::class)
            
            ->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'))
            ->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'))
        ;
    }
    
    protected function addElements(FormInterface $form, Producto $producto = null) {
        // 4. Add the province element
        $form->add('Producto', EntityType::class, array(
            'label' => 'Producto',
            'required' => true,
            'data' => $producto,
            'placeholder' => 'Seleccione un Producto...',
            'class' => Producto::class,
            'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->where('p.eliminado = false')
                            ->orderBy('p.nombre', 'ASC');
                    },
//            'choice_label' => function ($producto) {
//                      return $producto->getNombre();
//                },
        ));

        if($producto)
            {$idPto = $producto->getId();}
        else{$idPto = 0;}
        
        $form->add('precio', NumberType::class, array(
            'label' => 'Precio',
            'required' => true,
 //           'data' => $producto->getPrecio()
        ));
   
    }
    
    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        
         //Search for selected City and convert it into an Entity
        $producto = $this->em->getRepository(Producto::class)->find($data['Producto']);
        $this->addElements($form, $producto);
     }
     
    function onPreSetData(FormEvent $event) {
        if (null != $event->getData()) {
    
            $renglonHistorial = $event->getData();
            $form = $event->getForm();

            // When you create a new person, the City is always empty
            $producto = $renglonHistorial->getProducto() ? $producto->getProducto() : null;

            $this->addElements($form, $producto);
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_renglonHistorial';
    }
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RenglonHistorial::class,
        ]);
    }
}
