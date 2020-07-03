<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\ProductoType;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTableFactory;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\QueryBuilder;

class ProductoController extends AbstractController
{
    /**
     * @Route("/producto/nuevo/", name="producto_nuevo")
     */
    public function productoNuevoAction(Request $request)
    {
        $producto = new Producto();

        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(ProductoType::class, $producto);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $producto = $form->getData();
            $producto->setEliminado(false);
            $entityManager->persist($producto);
            $entityManager->flush();
            
            return $this->redirectToRoute('productos_listar');

        }
        return $this->render('producto/nuevo.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/producto/listado/", name="productos_listar")
     */
    public function showAction(Request $request, DataTableFactory $dataTableFactory)
    {
        //$entityManager = $this->getDoctrine()->getManager();
        //$builder = $entityManager->createQueryBuilder();
        
        $table = $dataTableFactory->create()
            ->add('id', TextColumn::class, ['visible' => false])
            ->add('nombre', TextColumn::class, ['label' => 'Nombre'])
            ->add('marca', TextColumn::class, ['label' => 'Marca'])
            ->add('descripcion', TextColumn::class, ['label' => 'Descripción'])
            ->add('cantidad', TextColumn::class, ['label' => 'Cantidad'])
            ->add('precio', TextColumn::class, ['label' => 'Precio'])
            ->add('buttons', TwigColumn::class, [
                'label' => 'Opciones',
                'className' => 'buttons',
                'template' => 'producto/botonesTabla.html.twig',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Producto::class,
                'query' => function (QueryBuilder $builder) {
                $builder
                    ->select('p')
                    //->addSelect('c')
                    ->from(Producto::class, 'p')
                    //->leftJoin('e.company', 'c')
                    ->where('p.eliminado = false')
                ;
                },
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('producto/listaProducto.html.twig', ['datatable' => $table]);
    }
    
    /**
     * @Route("/producto/modificar/{id}", name="producto_modificar")
     */
    public function productoModificarAction(Request $request, $id)
    {
            
        $entityManager = $this->getDoctrine()->getManager();

        $producto = $entityManager
            ->getRepository(Producto::class)
            ->find($id);

        $form = $this->createForm(ProductoType::class, $producto);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $producto = $form->getData();

//            $entityManager->persist($formulario);
            $entityManager->flush();
            
            return $this->redirectToRoute('productos_listar');

        }
        return $this->render('producto/nuevo.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/producto/eliminar/{id}", name="producto_eliminar")
     */
    public function productoEliminarAction(Request $request, $id)
    {     
        $entityManager = $this->getDoctrine()->getManager();

        $producto = $entityManager
            ->getRepository(Producto::class)
            ->find($id);

        try{
            $entityManager->remove($producto);
            $entityManager->flush();
        } catch (\Doctrine\DBAL\DBALException $e) {
            
            $exception_message = $e->getPrevious()->getCode();
            
            if($exception_message == 23000){
                
                if (!$entityManager->isOpen()) {
                    $this->getDoctrine()->resetManager();
                    $entityManager = $this->getDoctrine()->getManager();
                    $producto = $entityManager
                        ->getRepository(Producto::class)
                        ->find($id);
                }
                
                $producto->setEliminado(true);
                $entityManager->persist($producto);
                $entityManager->flush();
                
            }else{
                return $this->render('error.html.twig', array('error' => $exception_message));
            }

        } 

        return $this->redirectToRoute('productos_listar');
    }
    
    /**
     * @Route("/producto/preeliminar/{id}", name="producto_preeliminar")
     */
    public function productoPreEliminarAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $producto = $entityManager
            ->getRepository(Producto::class)
            ->find($id);
        
        return $this->render('producto/conf_Eliminar.html.twig', array('producto' => $producto)); 
    }
}
