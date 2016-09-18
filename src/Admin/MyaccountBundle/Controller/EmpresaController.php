<?php

namespace Admin\MyaccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Admin\AdminBundle\Pagination\Paginator;
use Admin\AdminBundle\Entity\AgenciaHojadevida;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 *
 * @Route("/Empresa")
 */
class EmpresaController extends Controller {

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/", name="Myaccount_empresa")
     * @Method("GET")
     * @Template()
     */
    public function IndexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $security_context = $this->get('security.context');
        //definimos el usuario logeado
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferente a cordinador, administrador,suberadmin
        $user = $security_token->getUser();
        //sacamos parametros de busqueda 
        $page = $request->query->get('page');
        $searchParam = $request->get('searchParam');
        $query = $em->createQuery(
                        'SELECT uh
                        FROM AdminAdminBundle:UsuarioHojadevida uh
                        WHERE uh.idUsuario  =:id'
                )->setParameter('id', $user->getId());
        if (!$query->getResult()) {
            return $this->redirect($this->generateUrl('Myaccount_perfil_new'));
        } else {
            $Hojadevida = $query->getResult();
            // Buscamos el array de resultados
            $Hojadevida = $query->setMaxResults(1)->getOneOrNullResult();
            $query = $em->createQuery(
                            'SELECT hp
                        FROM AdminAdminBundle:HojadevidaPhoto hp
                        WHERE hp.idHojadevida  =:id'
                    )->setParameter('id', $Hojadevida->getIdHojadevida()->getId());
            if ($query->getResult()) {
                $photo = true;
            } else {
                $photo = false;
            }
            $query = $em->createQuery(
                            'SELECT hp
                        FROM AdminAdminBundle:HojadevidaPhoto hp
                        WHERE (hp.idHojadevida  =:id) AND (hp.principal =:Valor)'
                    )->setParameter('id', $Hojadevida->getIdHojadevida()->getId())
                    ->setParameter('Valor', 1);
            if ($query->getResult()) {
                $HojadevidaP = $query->getResult();
                // Buscamos el array de resultados
                $HojadevidaP = $query->setMaxResults(1)->getOneOrNullResult();
                $idfoto = $HojadevidaP->getIdPhoto()->getId();
                $Image = $HojadevidaP->getIdPhoto()->getImage();
            } else {
                $idfoto = null;
                $Image = null;
            }
        }
        return $this->render('AdminMyaccountBundle:Empresa:index.html.twig', array(
                    'current_page' => $page,
                    'searchParam' => $searchParam,
                    'photo' => $photo,
                    'Image' => $Image,
                    'idfoto' => $idfoto
        ));
    }

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/mi/", name="Myaccount_misempresas")
     * @Method("GET")
     * @Template()
     */
    public function Index2Action(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $security_context = $this->get('security.context');
        //definimos el usuario logeado
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferente a cordinador, administrador,suberadmin
        $user = $security_token->getUser();
        //sacamos parametros de busqueda 
        $page = $request->query->get('page');
        $searchParam = $request->get('searchParam');
        $query = $em->createQuery(
                        'SELECT uh
                        FROM AdminAdminBundle:UsuarioHojadevida uh
                        WHERE uh.idUsuario  =:id'
                )->setParameter('id', $user->getId());
        if (!$query->getResult()) {
            return $this->redirect($this->generateUrl('Myaccount_perfil_new'));
        } else {
            $Hojadevida = $query->getResult();
            // Buscamos el array de resultados
            $Hojadevida = $query->setMaxResults(1)->getOneOrNullResult();
            $query = $em->createQuery(
                            'SELECT hp
                        FROM AdminAdminBundle:HojadevidaPhoto hp
                        WHERE hp.idHojadevida  =:id'
                    )->setParameter('id', $Hojadevida->getIdHojadevida()->getId());
            if ($query->getResult()) {
                $photo = true;
            } else {
                $photo = false;
            }
            $query = $em->createQuery(
                            'SELECT hp
                        FROM AdminAdminBundle:HojadevidaPhoto hp
                        WHERE (hp.idHojadevida  =:id) AND (hp.principal =:Valor)'
                    )->setParameter('id', $Hojadevida->getIdHojadevida()->getId())
                    ->setParameter('Valor', 1);
            if ($query->getResult()) {
                $HojadevidaP = $query->getResult();
                // Buscamos el array de resultados
                $HojadevidaP = $query->setMaxResults(1)->getOneOrNullResult();
                $idfoto = $HojadevidaP->getIdPhoto()->getId();
                $Image = $HojadevidaP->getIdPhoto()->getImage();
            } else {
                $idfoto = null;
                $Image = null;
            }
        }
        return $this->render('AdminMyaccountBundle:Empresa:index2.html.twig', array(
                    'current_page' => $page,
                    'searchParam' => $searchParam,
                    'photo' => $photo,
                    'Image' => $Image,
                    'idfoto' => $idfoto
        ));
    }

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/show/ajax", name="Myaccount_empresa_ajax")
     * @Method("GET")
     */
    public function AjaxAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        //////////////////buscando y paginando empresas
        //pagina donde se esta ubicado
        $page = $request->query->get('page');
        //Asignamos el parametro url
        $searchParam = $request->get('searchParam');
        //extraemos variables del array
        extract($searchParam);
        /// vamos a validar si ya ahi una hoja de vida registrada, si no la ahi 
        //entonces enviar usuario al formulario de registro de hoja de vida
        $security_context = $this->get('security.context');
        //definimos el usuario logeado
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferente a cordinador, administrador,suberadmin
        $user = $security_token->getUser();
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            $query = $em->createQuery(
                            'SELECT uh
                        FROM AdminAdminBundle:UsuarioHojadevida uh
                        WHERE uh.idUsuario  =:id'
                    )->setParameter('id', $user->getId());
            if (!$query->getResult()) {
                return $this->redirect($this->generateUrl('Myaccount_perfil_new'));
            } else {
                $query = $em->createQuery(
                                'SELECT uh
                        FROM AdminAdminBundle:UsuarioHojadevida uh
                        WHERE uh.idUsuario  =:id'
                        )->setParameter('id', $user->getId());
                if ($query->getResult()) {
                    /// solo mostramos una hoja de vida
                    $UHojadevida = $query->setMaxResults(1)->getOneOrNullResult();
                    $query = $em->createQuery(
                                    'SELECT ah
                        FROM AdminAdminBundle:AgenciaHojadevida ah
                        WHERE ah.idHojadevida  =:id'
                            )->setParameter('id', $UHojadevida->getIdHojadevida());
                    $AHojadevida = $query->getResult();
                }
            }
        } else {
            $AHojadevida = null;
        }
        //////////////////buscando y paginando empresas
        $entryQuery = $em->createQueryBuilder()
                ->select('ap', 'p', 'a')
                ->from('AdminAdminBundle:AgenciaPhoto', 'ap')
                ->leftJoin('ap.idAgencia', 'a')
                ->leftJoin('ap.idPhoto', 'p')
                ->andWhere('a.Activo =:estado')
                ->andWhere('a.privado =0')
                ->setParameter('estado', '1');
        //query aux
        $queryaux = $em->createQueryBuilder()
                ->select('COUNT(ap)')
                ->from('AdminAdminBundle:AgenciaPhoto', 'ap')
                ->leftJoin('ap.idAgencia', 'a')
                ->leftJoin('ap.idPhoto', 'p')
                ->andWhere('a.privado =0')
                ->andWhere('a.Activo =:estado')
                ->setParameter('estado', '1');
        if (!empty($general)) {
            $entryQuery->andWhere(''
                    . 'a.nombre_agencia Like :general or '
                    . 'a.ciudad Like :general or '
                    . 'a.categoria Like :general')->setParameter('general', '%' . $general . '%');
        }
        if (!empty($categoria)) {
            $entryQuery->andWhere('a.categoria =:categoria')->setParameter('categoria', $categoria);
        }
        $total_count = $queryaux->getQuery()->getSingleScalarResult();
        if (!empty($perPage))
            $entryQuery->setFirstResult(($page - 1) * $perPage)->setMaxResults($perPage);
        $entryQueryfinal = $entryQuery->getQuery();
        //obtenemos el array de resultados
        $entities = $entryQueryfinal->getArrayResult();
        $pagination = (new Paginator())->setItems($total_count, 10)->setPage(1)->toArray();

        return $this->render('AdminMyaccountBundle:Empresa:ajax.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
                    'AHojadevida' => $AHojadevida
        ));
    }

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/show/ajax2", name="Myaccount_empresa_ajax2")
     * @Method("GET")
     */
    public function Ajax2Action(Request $request) {
        $em = $this->getDoctrine()->getManager();
        //////////////////buscando y paginando empresas
        //pagina donde se esta ubicado
        $page = $request->query->get('page');
        //Asignamos el parametro url
        $searchParam = $request->get('searchParam');
        //extraemos variables del array
        extract($searchParam);
        /// vamos a validar si ya ahi una hoja de vida registrada, si no la ahi 
        //entonces enviar usuario al formulario de registro de hoja de vida
        $security_context = $this->get('security.context');
        //definimos el usuario logeado
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferente a cordinador, administrador,suberadmin
        $user = $security_token->getUser();
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            $query = $em->createQuery(
                            'SELECT uh
                        FROM AdminAdminBundle:UsuarioHojadevida uh
                        WHERE uh.idUsuario  =:id'
                    )->setParameter('id', $user->getId());
            if (!$query->getResult()) {
                return $this->redirect($this->generateUrl('Myaccount_perfil_new'));
            } else {
                $query = $em->createQuery(
                                'SELECT uh
                        FROM AdminAdminBundle:UsuarioHojadevida uh
                        WHERE uh.idUsuario  =:id'
                        )->setParameter('id', $user->getId());
                if ($query->getResult()) {
                    /// solo mostramos una hoja de vida
                    $UHojadevida = $query->setMaxResults(1)->getOneOrNullResult();
                    $query = $em->createQuery(
                                    'SELECT ah
                        FROM AdminAdminBundle:AgenciaHojadevida ah
                        WHERE ah.idHojadevida  =:id'
                            )->setParameter('id', $UHojadevida->getIdHojadevida());
                    $AHojadevida = $query->getResult();
                    foreach ($AHojadevida as $aux) {
                $ids[] = $aux->getIdAgencia(); //convertimos la consulta en un array
            }
                }
            }
        } else {
            $AHojadevida = null;
             $ids[] = null;
        }
        //////////////////buscando y paginando empresas
        $entryQuery = $em->createQueryBuilder()
                ->select('ap', 'p', 'a')
                ->from('AdminAdminBundle:AgenciaPhoto', 'ap')
                ->leftJoin('ap.idAgencia', 'a')
                ->leftJoin('ap.idPhoto', 'p')
                ->andWhere('a.Activo =:estado')
                ->andWhere('a.id in (:ids)')
                ->setParameter('ids',$ids)
                ->setParameter('estado', '1');
        //query aux
        $queryaux = $em->createQueryBuilder()
                ->select('COUNT(ap)')
                ->from('AdminAdminBundle:AgenciaPhoto', 'ap')
                ->leftJoin('ap.idAgencia', 'a')
                ->leftJoin('ap.idPhoto', 'p')
                ->andWhere('a.Activo =:estado')
                ->setParameter('estado', '1');
        if (!empty($general)) {
            $entryQuery->andWhere(''
                    . 'a.nombre_agencia Like :general or '
                    . 'a.ciudad Like :general or '
                    . 'a.categoria Like :general')->setParameter('general', '%' . $general . '%');
        }
        $total_count = $queryaux->getQuery()->getSingleScalarResult();
        if (!empty($perPage))
            $entryQuery->setFirstResult(($page - 1) * $perPage)->setMaxResults($perPage);
        $entryQueryfinal = $entryQuery->getQuery();
        //obtenemos el array de resultados
        $entities = $entryQueryfinal->getArrayResult();
        $pagination = (new Paginator())->setItems($total_count, 10)->setPage(1)->toArray();

        return $this->render('AdminMyaccountBundle:Empresa:ajax2.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
                    'AHojadevida' => $AHojadevida
        ));
    }

    /**
     * Finds and displays a Agencia entity.
     *
     * @Route("/{id}", name="Myaccount_empresa_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminAdminBundle:Agencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agencia entity.');
        }



        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/sendbook/ajax", name="Myaccount_empresas_Ofertar")
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
                $AgenciaHojadevida->setActivo('1');
                $AgenciaHojadevida->setFecha($date);
                $AgenciaHojadevida->setFechaupdate($date);
                $em->persist($AgenciaHojadevida);
                $em->flush();
            }
        }
        return new Response('1');
    }

}
