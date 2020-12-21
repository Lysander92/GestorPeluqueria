<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cliente;
use App\Form\ClienteType;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Doctrine\ORM\QueryBuilder;

class ClienteController extends AbstractController
{

    /**
     * @Route("/cliente/nuevo/", name="cliente_nuevo")
     */
    public function clienteNuevoAction(Request $request)
    {
        $cliente = new Cliente();

        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(ClienteType::class, $cliente);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $cliente = $form->getData();
            $cliente->setEliminado(false);
            $entityManager->persist($cliente);
            $entityManager->flush();
            
            return $this->redirectToRoute('clientes_listar');

        }
        return $this->render('cliente/nuevo.html.twig',[
            'form' => $form->createView(),
            'cliente' => $cliente
        ]);
    }
    
    /**
     * @Route("/cliente/listado/", name="clientes_listar")
     */
    public function showAction(Request $request, DataTableFactory $dataTableFactory)
    {
        $table = $dataTableFactory->create()
            ->add('id', TextColumn::class, ['visible' => false, 'searchable'  => false])
            ->add('apellido', TextColumn::class, ['label' => 'Apellido'])
            ->add('nombre', TextColumn::class, ['label' => 'Nombre'])
            ->add('direccion', TextColumn::class, ['label' => 'Dirección', 'searchable'  => false])
            ->add('telefono', TextColumn::class, ['label' => 'Teléfono', 'searchable'  => false])
            ->add('buttons', TwigColumn::class, [
                'label' => 'Opciones',
                'className' => 'buttons',
                'template' => 'cliente/botonesTabla.html.twig',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Cliente::class,
                'query' => function (QueryBuilder $builder) {
                $builder
                    ->select('c')
                    //->addSelect('c')
                    ->from(Cliente::class, 'c')
                    //->leftJoin('e.company', 'c')
                    ->where('c.eliminado = false')
                ;
                },
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('cliente/listaCliente.html.twig', ['datatable' => $table]);
    }
    
    /**
     * @Route("/cliente/modificar/{id}", name="cliente_modificar")
     */
    public function clienteModificarAction(Request $request, $id)
    {
            
        $entityManager = $this->getDoctrine()->getManager();

        $cliente = $entityManager
            ->getRepository(Cliente::class)
            ->find($id);

        $form = $this->createForm(ClienteType::class, $cliente);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $cliente = $form->getData();

//            $entityManager->persist($formulario);
            $entityManager->flush();
            
            return $this->redirectToRoute('clientes_listar');

        }
        return $this->render('cliente/nuevo.html.twig',[
            'form' => $form->createView(),
            'cliente' => $cliente
        ]);
    }
    
    /**
     * @Route("/cliente/eliminar/{id}", name="cliente_eliminar")
     */
    public function clienteEliminarAction(Request $request, $id)
    {
            
        $entityManager = $this->getDoctrine()->getManager();

        $cliente = $entityManager
            ->getRepository(Cliente::class)
            ->find($id);

      try{
            $entityManager->remove($cliente);
            $entityManager->flush();
        } catch (\Doctrine\DBAL\DBALException $e) {
            
            $exception_message = $e->getPrevious()->getCode();
            
            if($exception_message == 23000){
                
                if (!$entityManager->isOpen()) {
                    $this->getDoctrine()->resetManager();
                    $entityManager = $this->getDoctrine()->getManager();
                    $cliente = $entityManager
                        ->getRepository(Cliente::class)
                        ->find($id);
                }
                
                $cliente->setEliminado(true);
                $entityManager->persist($cliente);
                $entityManager->flush();
                
            }else{
                return $this->render('error.html.twig', array('error' => $exception_message));
            }

        }

        return $this->redirectToRoute('clientes_listar');
    }
    
    /**
     * @Route("/cliente/preeliminar/{id}", name="cliente_preeliminar")
     */
    public function clientePreEliminarAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $cliente = $entityManager
            ->getRepository(Cliente::class)
            ->find($id);
        
        return $this->render('cliente/conf_Eliminar.html.twig', array('cliente' => $cliente)); 
    }
    
    /**
     * @Route("/cliente/verHistoriales/{id}", name="cliente_historiales")
     */
    public function clienteVerHistorialesAction(Request $request, $id)
    {     
        $entityManager = $this->getDoctrine()->getManager();

        $cliente = $entityManager
            ->getRepository(Cliente::class)
            ->find($id);

        return $this->render('cliente/verHistorialesCliente.html.twig',[
            'Cliente' => $cliente,
        ]);
    }
}

