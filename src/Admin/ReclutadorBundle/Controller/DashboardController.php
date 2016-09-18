<?php

namespace Admin\ReclutadorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 *
 * @Route("/Reclutador/dashboard")
 */
class DashboardController extends Controller {

    /**
     * @Route("/", name="reclutador_dashboard")
     * @Template()
     */
    public function indexAction() {
        $security_context = $this->get('security.context');
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $em = $this->getDoctrine()->getManager();
        $queryaux = $em->createQueryBuilder()
                    ->select('COUNT(rh)')
                    ->from('AdminAdminBundle:ReclutadorHojadevida', 'rh')
                    ->andWhere('rh.idUsuario =:id')
                    ->setParameter('id', $user->getId());
            $queryaux->andWhere('rh.Estado =:Estado')->setParameter('Estado', 'Pendiente');
            $totalbooksP = $queryaux->getQuery()->getSingleScalarResult();
            $queryaux->andWhere('rh.Estado =:Estado')->setParameter('Estado', 'Pagado');
            $totalbooksA = $queryaux->getQuery()->getSingleScalarResult();
            $queryaux->andWhere('rh.Estado =:Estado')->setParameter('Estado', 'Anulado');
            $totalbooksR = $queryaux->getQuery()->getSingleScalarResult();
            
//definimos el nombre para la ruta de fikner-page
            //$ruta = str_replace(' ', '_', $Agencia->getIdAgencia()->getNombreAgencia());
            return array(
                'totalbooksP' => $totalbooksP,
                'totalbooksA' => $totalbooksA,
                'totalbooksR' => $totalbooksR,
            );
    }

    public function agenciaplan($idAgencia) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT a.id,a.nombre_agencia,p.nombre, ap.fechaCreacion,s.codigo FROM  AdminAdminBundle:Agencia a 
INNER JOIN AdminAdminBundle:AgenciaPlan ap WITH a.id= ap.idAgencia 
INNER JOIN AdminAdminBundle:Plan p WITH p.id= ap.idPlan 
INNER JOIN AdminAdminBundle:PlanServicio ps WITH ps.idPlan =p.id
INNER JOIN AdminAdminBundle:Servicio s WITH s.id = ps.idServicio
WHERE a.id=:id'
                )->setParameter('id', $idAgencia);

        if ($query->getResult()) {
            $Agenciaplan = $query->getResult();
        } else {
            $Agenciaplan = null;
        }
        return $Agenciaplan;
    }

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/EmpresasOfertar", name="Myaccount_empresas_Ofertar")
     * @Method("GET")
     */
    public function empresasOfertarAction(Request $request) {
        $security_context = $this->get('security.context');
        $em = $this->getDoctrine()->getEntityManager();
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $ids = $request->get('entities');
        $query = $em->createQuery(
                        'SELECT h
                            FROM AdminAdminBundle:Hojadevida h
                            WHERE h.emailPersonal =:email'
                )->setParameter('email', $user->getEmail());
        $Hojadevida = $query->getResult();
        // Buscamos el array de resultados
        $Hojadevida = $query->setMaxResults(1)->getOneOrNullResult();
        $query = $em->createQuery(
                        'SELECT a
                            FROM AdminAdminBundle:Agencia a
                            WHERE a.id in (:ids)'
                )->setParameter('ids', $ids);
        $Agencias = $query->getResult();
        $date = new \DateTime('now', new \DateTimeZone('America/Bogota'));
        foreach ($Agencias as $agencia) {
            $query = $em->createQuery(
                            'SELECT ah
                            FROM AdminAdminBundle:AgenciaHojadevida ah
                            WHERE (ah.idAgencia =:idagencia and ah.idHojadevida=:idHojadevida)'
                    )->setParameter('idagencia', $agencia->getId())
                    ->setParameter('idHojadevida', $Hojadevida->getId());
            if (!$query->getResult()) {
                $Agencias = $query->getResult();
                $AgenciaHojadevida = new AgenciaHojadevida();
                $AgenciaHojadevida->setIdAgencia($agencia);
                $AgenciaHojadevida->setIdHojadevida($Hojadevida);
                $AgenciaHojadevida->setEstado("Inactivo");
                $AgenciaHojadevida->setFecha($date);
                $AgenciaHojadevida->setFechaupdate($date);
                $em->persist($AgenciaHojadevida);
                $em->flush();
            }
        }
        return new Response('1');
    }

}
