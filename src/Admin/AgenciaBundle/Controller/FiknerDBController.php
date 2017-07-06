<?php

namespace Admin\AgenciaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Admin\AgenciaBundle\Entity\Hojadevida;
use Admin\AdminBundle\Entity\AgenciaHojadevida;
use Admin\AgenciaBundle\Form\AgenciaHojadevidaType;
use Admin\AdminBundle\Pagination\Paginator;
use DateTime;

/**
 * Hojadevida controller.
 *
 * @Route("/Agencia/dashboard/fiknerdb")
 */
class FiknerDBController extends Controller {

    /**
     * Lists all Hojadevida entities.
     *
     * @Route("/", name="agencia_dashboard_fiknerdb")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $security_context = $this->get('security.context');
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $request = $this->getRequest();
        //Asignamos el parametro url para luego pasarlo a ajax
        $page = $request->query->get('page');
        $searchParam = $request->get('searchParam');
        ///verificamos si existe una agencia registrada con el usuario
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT au
                        FROM AdminAdminBundle:AgenciaUsuario au
                        WHERE au.idUsuario  =:id'
                )->setParameter('id', $user->getId());
        if ($query->getResult()) {
            $agencia = true;
            $AgenciaUsuario = $query->getResult();
            // Buscamos el array de resultados
            $AgenciaUsuario = $query->setMaxResults(1)->getOneOrNullResult();
            ///verificamos plan que tiene la agencia
            $agenciaPlan =$em->getRepository('AdminAdminBundle:Agencia')->plan($AgenciaUsuario->getIdAgencia()->getId());
            if(!$agenciaPlan){
                $agenciaPlan=null;
            }
        } else {
            $agencia = false;
            $AgenciaUsuario = null;
            $agenciaPlan = null;
        }



        return array(
            'current_page' => $page,
            'searchParam' => $searchParam,
            'agencia' => $agencia,
            'AgenciaUsuario' => $AgenciaUsuario,
            'AgenciaP' => $agenciaPlan
        );
    }


    /**
     * consulta a Hojadevida entity.
     *
     * @Route("/ajax/consulta", name="agencia_dashboard_fiknerdb_ajax")
     * @Method("GET")
     */
    public function ajaxListAction(Request $request) {
        //verificamos que usuario se encuentra logeado
        $security_context = $this->get('security.context');
        $em = $this->getDoctrine()->getManager();
        //pagina donde se esta ubicado
        $page = $request->query->get('page');
        //Asignamos el parametro url
        $searchParam = $request->get('searchParam');
        //extraemos variables del array
        extract($searchParam);
            $entryQuery = $em->createQueryBuilder()
                    ->select('hp', 'p', 'h')
                    ->from('AdminAdminBundle:HojadevidaPhoto', 'hp')
                    ->andWhere('hp.principal = 1')
                    ->leftJoin('hp.idPhoto', 'p')
                    ->leftJoin('hp.idHojadevida', 'h');
            $queryaux = $em->createQueryBuilder()
                    ->select('COUNT(hp)')
                    ->from('AdminAdminBundle:HojadevidaPhoto', 'hp')
                    ->andWhere('hp.principal = 1')
                    ->leftJoin('hp.idPhoto', 'p')
                    ->leftJoin('hp.idHojadevida', 'h');
        if(!empty($calificacion!='Todas')){
            $entryQuery->andWhere('h.Calificacion =:calificacion ')->setParameter('calificacion', intval($calificacion));
            $queryaux->andWhere('h.Calificacion =:calificacion')->setParameter('calificacion', intval($calificacion));
        }


        if (!empty($id)) {
            $entryQuery->andWhere('h.id =:id ')->setParameter('id', $id);
            $queryaux->andWhere('h.id =:id ')->setParameter('id', $id);
        }
        if (!empty($Nombres)) {
            $entryQuery->andWhere('h.nombre Like :nombre ')->setParameter('nombre', $Nombres);
            $queryaux->andWhere('h.nombre Like :nombre ')->setParameter('nombre', $Nombres);
            ;
        }
        if (!empty($Apellidos)) {
            $entryQuery->andWhere('h.apellido Like :apellido')->setParameter('apellido', $Apellidos);
            $queryaux->andWhere('h.apellido Like :apellido')->setParameter('apellido', $Apellidos);
        }
        if (!empty($ciudadresidencia)) {
            $entryQuery->andWhere('h.ciudadresidencia =:ciudadresidencia ')->setParameter('ciudadresidencia', $ciudadresidencia);
            $queryaux->andWhere('h.ciudadresidencia =:ciudadresidencia ')->setParameter('ciudadresidencia', $ciudadresidencia);
        }
        if (!empty($telCe)) {
            $entryQuery->andWhere('h.telCe =:telCe ')->setParameter('telCe', $telCe);
            $queryaux->andWhere('h.telCe =:telCe ')->setParameter('telCe', $telCe);
        }
        if (!empty($nit)) {
            $entryQuery->andWhere('h.nit =:nit ')->setParameter('nit', $nit);
            $queryaux->andWhere('h.nit =:nit ')->setParameter('nit', $nit);
        }
        if (!empty($sexo)) {
            $entryQuery->andWhere('h.sexo =:sexo ')->setParameter('sexo', $sexo);
            $queryaux->andWhere('h.sexo =:sexo ')->setParameter('sexo', $sexo);
        }
        if (!empty($lugarNacimiento)) {
            $entryQuery->andWhere('h.paisNacimiento Like :lugarNacimiento ')->setParameter('lugarNacimiento', $lugarNacimiento);
            $queryaux->andWhere('h.paisNacimiento Like :lugarNacimiento ')->setParameter('lugarNacimiento', $lugarNacimiento);
        }
        //verifamos para que solo mueste sobre el rango de edad
        if ($EdadMin != null && $EdadMax != null) {
            $date = new \DateTime('now');
            if ($EdadMin > $EdadMax) {
                $aux = $EdadMin;
                $EdadMin = $EdadMax;
                $EdadMax = $aux;
            }
            $entryQuery->andWhere('((:now - YEAR(h.fechaNac)) >= :EdadMin ) and ((:now - YEAR(h.fechaNac)) <= :EdadMax )')
                    ->setParameter('now', $date->format('Y'))
                    ->setParameter('EdadMin', $EdadMin)
                    ->setParameter('EdadMax', $EdadMax);
            $queryaux->andWhere('((:now - YEAR(h.fechaNac)) >= :EdadMin ) and ((:now - YEAR(h.fechaNac)) <= :EdadMax )')
                    ->setParameter('now', $date->format('Y'))
                    ->setParameter('EdadMin', $EdadMin)
                    ->setParameter('EdadMax', $EdadMax);
        }
        if (!empty($EstaturaMin) && !empty($EstaturaMax)) {
            if ($EstaturaMin > $EstaturaMax) {
                $aux = $EstaturaMin;
                $EstaturaMin = $EstaturaMax;
                $EstaturaMax = $aux;
            }
            $entryQuery->andWhere('( h.estatura >= :EstaturaMin ) and (h.estatura <= :EstaturaMax )')
                    ->setParameter('EstaturaMin', $EstaturaMin)
                    ->setParameter('EstaturaMax', $EstaturaMax);
            $queryaux->andWhere('( h.estatura >= :EstaturaMin ) and (h.estatura <= :EstaturaMax )')
                    ->setParameter('EstaturaMin', $EstaturaMin)
                    ->setParameter('EstaturaMax', $EstaturaMax);
        }
        if (!empty($piel)) {
            $entryQuery->andWhere('h.piel =:piel ')->setParameter('piel', $piel);
            $queryaux->andWhere('h.piel =:piel ')->setParameter('piel', $piel);
        }
        if (!empty($ojos)) {
            $entryQuery->andWhere('h.ojos =:ojos ')->setParameter('ojos', $ojos);
            $queryaux->andWhere('h.ojos =:ojos ')->setParameter('ojos', $ojos);
        }
        if (!empty($pelo)) {
            $entryQuery->andWhere('h.pelo =:pelo ')->setParameter('pelo', $pelo);
            $queryaux->andWhere('h.pelo =:pelo ')->setParameter('pelo', $pelo);
        }
        if (!empty($pesoMin) && !empty($pesoMax)) {
            if ($pesoMin > $pesoMax) {
                $aux = $pesoMin;
                $pesoMin = $pesoMax;
                $pesoMax = $aux;
            }
            $entryQuery->andWhere('( h.peso >= :pesoMin ) and (h.peso <= :pesoMax )')
                    ->setParameter('pesoMin', $pesoMin)
                    ->setParameter('pesoMax', $pesoMax);
            $queryaux->andWhere('( h.peso >= :pesoMin ) and (h.peso <= :pesoMax )')
                    ->setParameter('pesoMin', $pesoMin)
                    ->setParameter('pesoMax', $pesoMax);
        }
        if (!empty($deportes)) {
            $entryQuery->andWhere('h.deportes Like :deportes ')->setParameter('deportes', '%' . $deportes . '%');
            $queryaux->andWhere('h.deportes Like :deportes ')->setParameter('deportes', '%' . $deportes . '%');
        }
        if (!empty($habilidades)) {
            $entryQuery->andWhere('h.habilidades Like :habilidades ')->setParameter('habilidades', '%' . $habilidades . '%');
            $queryaux->andWhere('h.habilidades Like :habilidades ')->setParameter('habilidades', '%' . $habilidades . '%');
        }
        if (!empty($idiomas)) {
            $entryQuery->andWhere('h.idiomas Like :idiomas ')->setParameter('idiomas', '%' . $idiomas . '%');
            $queryaux->andWhere('h.idiomas Like :idiomas ')->setParameter('idiomas', '%' . $idiomas . '%');
        }
        if (!empty($maneja)) {
            $entryQuery->andWhere('h.maneja =:maneja ')->setParameter('maneja', $maneja);
            $queryaux->andWhere('h.maneja =:maneja ')->setParameter('maneja', $maneja);
        }
        if (!empty($entidadSalud)) {
            $entryQuery->andWhere('h.entidadSalud Like :entidadSalud ')->setParameter('entidadSalud', '%' . $entidadSalud . '%');
            $queryaux->andWhere('h.entidadSalud Like :entidadSalud')->setParameter('entidadSalud', '%' . $entidadSalud . '%');
        }
        if (!empty($categoria)) {
            $entryQuery->andWhere('h.categoria =:categoria ')->setParameter('categoria', $categoria);
            $queryaux->andWhere('h.categoria =:categoria ')->setParameter('categoria', $categoria);
        }
        if (!empty($tallaCamisa)) {
            $entryQuery->andWhere('h.tallaCamisa =:tallaCamisa')->setParameter('tallaCamisa', $tallaCamisa);
            $queryaux->andWhere('h.tallaCamisa=:tallaCamisa')->setParameter('tallaCamisa', $tallaCamisa);
        }
        if (!empty($tallaPantalon)) {
            $entryQuery->andWhere('h.tallaPantalon =:tallaPantalon')->setParameter('tallaPantalon', $tallaPantalon);
            $queryaux->andWhere('h.tallaPantalon=:tallaPantalon')->setParameter('tallaPantalon', $tallaPantalon);
        }
        if (!empty($tallaZapato)) {
            $entryQuery->andWhere('h.tallaZapato =:tallaZapato')->setParameter('tallaZapato', $tallaZapato);
            $queryaux->andWhere('h.tallaZapato=:tallaZapato')->setParameter('tallaZapato', $tallaZapato);
        }
        if (!empty($Tags)) {
            $entryQuery->andWhere('ah.Tags Like :Tags')->setParameter('Tags', '%' . $Tags . '%');
            $queryaux->andWhere('ah.Tags Like :Tags')->setParameter('Tags', '%' . $Tags . '%');
        }
        $total_count = $queryaux->getQuery()->getSingleScalarResult();
        if (!empty($perPage))
            $entryQuery->setFirstResult(($page - 1) * $perPage)->setMaxResults($perPage);
        $entryQueryfinal = $entryQuery->getQuery();
        //obtenemos el array de resultados
        $entities = $entryQueryfinal->getArrayResult();
        $pagination = (new Paginator())->setItems($total_count, $searchParam['perPage'])->setPage($searchParam['page'])->toArray();

        //Buscamos ids que ya tiene la agencia
        $security_context = $this->get('security.context');
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $query = $em->createQuery(
            'SELECT au
                        FROM AdminAdminBundle:AgenciaUsuario au
                        WHERE au.idUsuario  =:id'
        )->setParameter('id', $user->getId());
        if ($query->getResult()) {
            $agencia = true;
            $AgenciaUsuario = $query->getResult();
            $AgenciaUsuario = $query->setMaxResults(1)->getOneOrNullResult();
            $ids = $em->getRepository('AdminAdminBundle:AgenciaHojadevida')->idsHojasdevida($AgenciaUsuario->getIdAgencia()->getId());
        }
        //renderizamos la vista para mostrar las hojas de vida
        return $this->render('AdminAgenciaBundle:FiknerDB:ajax_list.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
                    'ids'=>$ids
        ));
    }
  /**
     * Finds and displays a Hojadevida entity.
     *
     * @Route("/{id}", name="agencia_dashboard_hojadevida_fiknerdb_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $security_context = $this->get('security.context');
        $security_token = $security_context->getToken();
        $user = $security_token->getUser();

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT AU
                            FROM AdminAdminBundle:AgenciaUsuario AU
                            WHERE AU.idUsuario  =:idUsuario'
                )->setParameter('idUsuario', $user->getId());
        if ($query->getResult()) {
            $agenciaU = $query->setMaxResults(1)->getOneOrNullResult();
            $query = $em->createQuery(
                                'SELECT hp
                        FROM AdminAdminBundle:HojadevidaPhoto hp
                        WHERE (hp.idHojadevida  =:id) AND (hp.principal =:Valor)'
                        )->setParameter('id', $id)
                        ->setParameter('Valor', 1);
            if ($query->getResult()) {
                    $HPhoto = $query->getResult();
                    // Buscamos el array de resultados
                    $HPhoto = $query->setMaxResults(1)->getOneOrNullResult();
            }
            else{            
                    $query = $em->createQuery(
                                    'SELECT HP
                                    FROM AdminAdminBundle:HojadevidaPhoto HP
                                    WHERE HP.idHojadevida  =:id'
                            )->setParameter('id', $id);
                if ($query->getResult()) {
                        $HPhoto = $query->setMaxResults(1)->getOneOrNullResult();
                }
            }
            $query = $em->createQuery(
                            'SELECT AH
                            FROM AdminAdminBundle:AgenciaHojadevida AH
                            WHERE (AH.idAgencia  =:idAgencia  AND AH.idHojadevida=:idhojadevida) AND  (AH.Activo  =:Activo)'
                    )->setParameter('idAgencia', $agenciaU->getIdAgencia()->getId())
                    ->setParameter('Activo', '1')
                    ->setParameter('idhojadevida', $HPhoto->getIdHojadevida());
            if ($query->getResult()) {
                $hojadevida = $query->setMaxResults(1)->getOneOrNullResult();
            } else {
                $hojadevida = NULL;
            }
        }
        //buscamos la agencia asignada al usuario 
        $query = $em->createQuery(
                        'SELECT AU
                            FROM AdminAdminBundle:AgenciaUsuario AU
                            WHERE AU.idUsuario  =:idUsuario'
                )->setParameter('idUsuario', $user->getId());
        if ($query->getResult()) {
            $agenciaU = $query->setMaxResults(1)->getOneOrNullResult();
            ///verificamos plan que tiene la agencia 
            $agenciaPlan =$em->getRepository('AdminAdminBundle:Agencia')->plan($agenciaU->getIdAgencia()->getId());
            if(!$agenciaPlan){
                $agenciaPlan=null;
            }
        } else {
            $agenciaPlan = null;
        }
        $seguimientoagencia = new \Admin\AdminBundle\Entity\SeguimientoAgencia();
        $seguimientoagencia->setIdAgencia($agenciaU->getIdAgencia());
        $seguimientoagencia->setIdHojadevida($HPhoto->getIdHojadevida());
        $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
        $seguimientoagencia->setFechavisita($date);
        $em->persist($seguimientoagencia);
        $em->flush();

        return array(
            'entity' => $HPhoto,
            'Image'=>$HPhoto->getIdPhoto()->getImage(),
            'idfoto' => $HPhoto->getIdPhoto()->getId(),
            'Ahojadevida' => $hojadevida,
            'AgenciaP'=>$agenciaPlan
        );
    }

    /**
     * Finds and displays a Hojadevida entity.
     *
     * @Route("/fullimage/{nit}/{id}/{foto}", name="agencia_dashboard_hojadevida_showfoto")
     * @Method("GET")
     * @Template()
     */
    public function showfotoAction($nit, $id, $foto) {
        //mostrarmos la foto origunal
        $request = $this->getRequest();
        return array(
            'foto' => $foto,
            'nit' => $nit,
            'id' => $id
        );
    }

}
