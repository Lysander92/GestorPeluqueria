<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Proveedor;
use App\Form\ProveedorType;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Doctrine\ORM\QueryBuilder;

class ProveedorController extends AbstractController
{

    /**
     * @Route("/proveedor/nuevo/", name="proveedor_nuevo")
     */
    public function proveedorNuevoAction(Request $request)
    {
        $proveedor = new Proveedor();

        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(ProveedorType::class, $proveedor);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $proveedor = $form->getData();
            $proveedor->setEliminado(false);
            $entityManager->persist($proveedor);
            $entityManager->flush();
            
            return $this->redirectToRoute('proveedor_listar');

        }
        return $this->render('proveedor/nuevo.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/proveedor/listado/", name="proveedor_listar")
     */
    public function showAction(Request $request, DataTableFactory $dataTableFactory)
    {
        $table = $dataTableFactory->create()
            ->add('id', TextColumn::class, ['visible' => false, 'searchable'  => false])
            ->add('nombre', TextColumn::class, ['label' => 'Nombre'])
            ->add('direccion', TextColumn::class, ['label' => 'Dirección', 'searchable'  => false])
            ->add('telefono', TextColumn::class, ['label' => 'Teléfono', 'searchable'  => false])
            ->add('buttons', TwigColumn::class, [
                'label' => 'Opciones',
                'className' => 'buttons',
                'template' => 'proveedor/botonesTabla.html.twig',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Proveedor::class,
                'query' => function (QueryBuilder $builder) {
                $builder
                    ->select('p')
                    //->addSelect('c')
                    ->from(Proveedor::class, 'p')
                    //->leftJoin('e.company', 'c')
                    ->where('p.eliminado = false')
                ;
                },
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('proveedor/listaProveedor.html.twig', ['datatable' => $table]);
    }
    
    /**
     * @Route("/proveedor/modificar/{id}", name="proveedor_modificar")
     */
    public function proveedorModificarAction(Request $request, $id)
    {
            
        $entityManager = $this->getDoctrine()->getManager();

        $proveedor = $entityManager
            ->getRepository(Proveedor::class)
            ->find($id);

        $form = $this->createForm(ProveedorType::class, $proveedor);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $proveedor = $form->getData();

//            $entityManager->persist($formulario);
            $entityManager->flush();
            
            return $this->redirectToRoute('proveedor_listar');

        }
        return $this->render('proveedor/nuevo.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/proveedor/eliminar/{id}", name="proveedor_eliminar")
     */
    public function proveedorEliminarAction(Request $request, $id)
    {
            
        $entityManager = $this->getDoctrine()->getManager();

        $proveedor = $entityManager
            ->getRepository(Proveedor::class)
            ->find($id);

      try{
            $entityManager->remove($proveedor);
            $entityManager->flush();
        } catch (\Doctrine\DBAL\DBALException $e) {
            
            $exception_message = $e->getPrevious()->getCode();
            
            if($exception_message == 23000){
                
                if (!$entityManager->isOpen()) {
                    $this->getDoctrine()->resetManager();
                    $entityManager = $this->getDoctrine()->getManager();
                    $proveedor = $entityManager
                        ->getRepository(Proveedor::class)
                        ->find($id);
                }
                
                $proveedor->setEliminado(true);
                $entityManager->persist($cliente);
                $entityManager->flush();
                
            }else{
                return $this->render('error.html.twig', array('error' => $exception_message));
            }

        }

        return $this->redirectToRoute('proveedor_listar');
    }
    
    /**
     * @Route("/proveedor/preeliminar/{id}", name="proveedor_preeliminar")
     */
    public function proveedorPreEliminarAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $proveedor = $entityManager
            ->getRepository(Proveedor::class)
            ->find($id);
        
        return $this->render('cliente/conf_Eliminar.html.twig', array('proveedor' => $proveedor)); 
    }
    
    /**
     * @Route("/proveedor/verHistoriales/{id}", name="proveedor_historiales")
     */
    public function proveedorVerHistorialesAction(Request $request, $id)
    {     
        $entityManager = $this->getDoctrine()->getManager();

        $proveedor = $entityManager
            ->getRepository(Proveedor::class)
            ->find($id);

        return $this->render('proveedor/verHistorialesProveedor.html.twig',[
            'Proveedor' => $proveedor,
        ]);
    }
}

