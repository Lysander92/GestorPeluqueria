<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Historial;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Doctrine\ORM\QueryBuilder;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        $conn = $this->getDoctrine()->getManager()->getConnection();
        
        
        $sql = "SELECT SUM(r.cantidad*r.precio) AS total "
                . "FROM historial h, cliente c, renglon_historial r "
                . "WHERE c.id = h.cliente_id "
                . "AND h.id = r.historial_id ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        //$stmt->fetchAll();
        //var_dump($stmt->fetch());die;

        
        
        return $this->render('home/index.html.twig', [
            'resultado' => $stmt->fetch(),
        ]);
    }
    
    /**
     * Returns a JSON string with the neighborhoods of the City with the providen id.
     * @Route("/home/estadisticas", name="calcular_estadisticas", defaults={"_controller": "App:Controller:HomeController:estadisticasAction"}, methods="GET")
     * @param Request $request
     * @return JsonResponse
     */
    public function estadisticasAction(Request $request)
    {

        $fechaDesde = $request->query->get("fechaDesde");
        $fechaHasta = $request->query->get("fechaHasta");
        
        $conn = $this->getDoctrine()->getManager()->getConnection();
        
        $sql = "SELECT SUM(r.cantidad*r.precio) AS ingresos_brutos "
                . "FROM historial h, cliente c, renglon_historial r "
                . "WHERE c.id = h.cliente_id "
                . "AND h.id = r.historial_id "
                . "AND h.fecha BETWEEN :fechaDesde AND :fechaHasta";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('fechaDesde' => $fechaDesde, 'fechaHasta' => $fechaHasta ));
        $respuesta = $stmt->fetch();
        
        $responseArray = array();
        
        $responseArray['ingresosBrutos'] = $respuesta['ingresos_brutos'];
        //var_dump($responseArray);die;
        
        $sql = "SELECT SUM(r.cantidad*r.precio) AS gastos "
                . "FROM historial h, proveedor p, renglon_historial r "
                . "WHERE p.id = h.proveedor_id "
                . "AND h.id = r.historial_id "
                . "AND h.fecha BETWEEN :fechaDesde AND :fechaHasta";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('fechaDesde' => $fechaDesde, 'fechaHasta' => $fechaHasta ));
        //$stmt->execute();
        $respuesta = $stmt->fetch();
        
        $responseArray['gastos'] = $respuesta['gastos'];
        
        $sql = "SELECT SUM(r.cantidad*r.precio) AS facturado "
                . "FROM historial h, cliente c, renglon_historial r "
                . "WHERE c.id = h.cliente_id "
                . "AND h.id = r.historial_id "
                . "AND h.factura = '1' "
                . "AND h.fecha BETWEEN :fechaDesde AND :fechaHasta";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('fechaDesde' => $fechaDesde, 'fechaHasta' => $fechaHasta ));
        $respuesta = $stmt->fetch();
        
        $responseArray['facturado'] = $respuesta['facturado'];
        //var_dump($responseArray);die;
        
        // Return array with structure of the neighborhoods of the providen city id
        return new JsonResponse($responseArray);

    }
}
