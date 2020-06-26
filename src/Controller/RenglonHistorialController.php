<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Historial;
use App\Entity\Producto;
use App\Entity\RenglonHistorial;
use App\Form\RenglonHistorialType;
use Symfony\Component\HttpFoundation\JsonResponse;

class RenglonHistorialController extends AbstractController
{
    
    /**
     * @Route("/renglonHistorial/nuevo/{historial_id}", name="renglonHistorial_nuevo")
     */
    public function renglonHistorialNuevoAction(Request $request, $historial_id)
    {
        $renglonHistorial = new RenglonHistorial();

        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(RenglonHistorialType::class, $renglonHistorial);

        $form->handleRequest($request);
        
        $historial = $entityManager
                        ->getRepository(Historial::class)
                        ->find($historial_id);
        
        $total = 0;
        foreach ($historial->getRenglonHistorial() as $renglon) {
            $total += $renglon->getPrecio();
        }
        
        if($form->isSubmitted() && $form->isValid()){

            $renglonHistorial = $form->getData();
            $renglonHistorial->setHistorial($historial);
            
            $entityManager->persist($renglonHistorial);
            $entityManager->flush();
            
            return $this->redirectToRoute('renglonHistorial_nuevo', array('historial_id' => $historial_id));

        }
        
        return $this->render('renglon_historial/nuevo.html.twig',[
            'form' => $form->createView(),
            'historial' => $historial,
            'total' => $total,
        ]);
    }
    
    /**
     * @Route("/renglonHistorial/eliminar/{id}", name="renglonHistorial_eliminar")
     */
    public function renglonHistorialEliminarAction(Request $request, $id)
    {    
        $entityManager = $this->getDoctrine()->getManager();

        $renglonHistorial = $entityManager
            ->getRepository(RenglonHistorial::class)
            ->find($id);

        $historial = $renglonHistorial->getHistorial();
        $historial->removeRenglonHistorial($renglonHistorial);
        //$entityManager->remove($renglonHistorial);

        $entityManager->flush();

        return $this->redirectToRoute('renglonHistorial_nuevo', array('historial_id' => $historial->getId()));
    }
    
    /**
     * Returns a JSON string with the neighborhoods of the City with the providen id.
     * @Route("/renglonHitorial/dependent", name="renglonHitorial_list_precios", defaults={"_controller": "App:Controller:RenglonHitorialController:listPrecioOfRenglonHitorialAction"}, methods="GET")
     * @param Request $request
     * @return JsonResponse
     */
    public function listPrecioOfRenglonHitorialAction(Request $request)
    {
        // Get Entity manager and repository
        $entityManager = $this->getDoctrine()->getManager();
        
        

// Search the neighborhoods that belongs to the city with the given id as GET parameter "cityid"
        $producto = $entityManager
                        ->getRepository(Producto::class)
                        ->find($request->query->get("idPto"));
        
//        $localidades = $localidadesRepository->createQueryBuilder("q")
//            ->where("q.idDepartamento = :idDto")
//            ->setParameter("idDto", $request->query->get("idDto"))
//            ->getQuery()
//            ->getResult();
        
        // Serialize into an array the data that we need, in this case only name and id
        // Note: you can use a serializer as well, for explanation purposes, we'll do it manually
        $responseArray = array();
//        foreach($localidades as $localidad){
        $responseArray[] = array(
            "precio" => $producto->getPrecio(),
            "detalle" => $producto->getDescripcion()
        );
//        }
        
        // Return array with structure of the neighborhoods of the providen city id
        return new JsonResponse($responseArray);

        // e.g
        // [{"id":"3","name":"Treasure Island"},{"id":"4","name":"Presidio of San Francisco"}]
    }
}
