<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Historial;
use App\Entity\Cliente;
use App\Form\HistorialType;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Adapter\Doctrine\ORM\SearchCriteriaProvider;
use Doctrine\ORM\QueryBuilder;

class HistorialController extends AbstractController
{
    /**
     * @Route("/historial/nuevo/", name="historial_nuevo")
     */
    public function historialNuevoAction(Request $request)
    {
        $historial = new Historial();

        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(HistorialType::class, $historial);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $historial = $form->getData();
            $historial->setEliminado(false);
            $entityManager->persist($historial);
            $entityManager->flush();
            
            //return $this->redirectToRoute('historiales_listar');
             return $this->redirectToRoute('renglonHistorial_nuevo', array('historial_id' => $historial->getId()));

        }
        return $this->render('historial/nuevo.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/historial/listado/", name="historiales_listar")
     */
    public function showAction(Request $request, DataTableFactory $dataTableFactory)
    { 
        $table = $dataTableFactory->create()
            ->add('id', TextColumn::class, ['visible' => false])
            ->add('Cliente', TextColumn::class, ['label' => 'Facturado', 'field' => 'Cliente.apellido']) 
            ->add('fecha', DateTimeColumn::class, [
                'label' => 'Fecha',
                'format' => 'd-m-Y',
                'searchable' => true
                ])
            ->add('factura', BoolColumn::class, ['label' => 'Facturado'])
            ->add('buttons', TwigColumn::class, [
                'label' => 'Opciones',
                'className' => 'buttons',
                'template' => 'historial/botonesTabla.html.twig',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Historial::class,
                'query' => function (QueryBuilder $builder) {
                $builder
                    ->select('h')
                    ->from(Historial::class, 'h')
                    ->where('h.eliminado = false')
                    ->leftJoin('h.Cliente', 'Cliente')
                ;
                },
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('historial/listaHistorial.html.twig', ['datatable' => $table]);
    }
    
    /**
     * @Route("/historial/eliminar/{id}", name="historial_eliminar")
     */
    public function historialEliminarAction(Request $request, $id)
    {
            
        $entityManager = $this->getDoctrine()->getManager();

        $historial = $entityManager
            ->getRepository(Historial::class)
            ->find($id);

        $renglonHistoriales = $historial->getRenglonHistorial();
        
        if($renglonHistoriales->isEmpty()){
            $entityManager->remove($historial);
            
        }else{
            $historial->setEliminado(true);
            $entityManager->persist($historial);
        }      
//        foreach ($renglonHistoriales as $renglonHistorial){
//            $historial->removeRenglonHistorial($renglonHistorial);
//        }

        $entityManager->flush();

//        return $this->redirectToRoute('historiales_listar');
        return $this->render('cliente/verHistorialesCliente.html.twig',[
            'Cliente' => $historial->getCliente(),
        ]);
    }
    
    /**
     * @Route("/historial/ver/{id}", name="historial_ver")
     */
    public function historialVerAction(Request $request, $id)
    {     
        $entityManager = $this->getDoctrine()->getManager();

        $historial = $entityManager
            ->getRepository(Historial::class)
            ->find($id);
        
        $total = 0;
        foreach ($historial->getRenglonHistorial() as $renglon) {
            $total += $renglon->getPrecio();
        }
        
        return $this->render('historial/ver.html.twig',[
            'historial' => $historial,
            'total' => $total,
        ]);
    }
    
    /**
     * @Route("/historial/preeliminar/{id}", name="historial_preeliminar")
     */
    public function historialPreEliminarAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $historial = $entityManager
            ->getRepository(Historial::class)
            ->find($id);
        
        return $this->render('historial/conf_Eliminar.html.twig', array('historial' => $historial)); 
    }
    
    
}
