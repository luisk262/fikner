<?php

namespace Admin\AgenciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use Admin\AdminBundle\Entity\AgenciaHojadevida;

class PageController extends Controller {
    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/agencia/{nombre}", name="Agencia_page")
     * @Method("GET")
     * @Template("AdminAgenciaBundle:Page:index.html.twig")
     */
    public function IndexAction($nombre) {
        $em = $this->getDoctrine()->getManager();

////verificamos si hay un usuario logeado
        $security_context = $this->get('security.context');
        $security_token = $security_context->getToken();
        $user = $security_token->getUser();
        $U_foto_Agencia = null;
        $U_foto = null;
        if ($security_context->isGranted('ROLE_USER') && !($security_context->isGranted('ROLE_AGENC'))) {
            $query = $em->createQuery(
                            'SELECT uh
                        FROM AdminAdminBundle:UsuarioHojadevida uh
                        WHERE uh.idUsuario  =:id'
                    )->setParameter('id', $user->getId());
            if ($query->getResult()) {
                ///SI EL USUARIO TIENE HOJA DE VIDA
                $Hojadevida = $query->getResult();
                // Buscamos el array de resultados
                $Hojadevida = $query->setMaxResults(1)->getOneOrNullResult();
                $query = $em->createQuery(
                                'SELECT hp
                        FROM AdminAdminBundle:HojadevidaPhoto hp
                        WHERE hp.idHojadevida  =:id'
                        )->setParameter('id', $Hojadevida->getIdHojadevida()->getId());
                if ($query->getResult()) {
                    //SI LA HOJA DE VIDA TIENE FOTO
                    $query = $em->createQuery(
                                    'SELECT AH
                        FROM AdminAdminBundle:AgenciaHojadevida AH
                        WHERE AH.idHojadevida  =:id
                        ORDER BY AH.fechaupdate DESC'
                            )->setParameter('id', $Hojadevida->getIdHojadevida()->getId());
                    if ($query->getResult()) {
                        $AgenciaHojadevida = $query->getResult();
                        $U_foto_Agencia = $AgenciaHojadevida;
                    } else {
                        $U_foto_Agencia = false;
                    }
                    $U_foto = true;
                } else {
                    $U_foto_Agencia = false;
                    $U_foto = false;
                }
            }
        }
        $Nombre_Agencia = str_replace('_', ' ', $nombre);
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT a
                        FROM AdminAdminBundle:Agencia a
                        WHERE a.nombre_agencia  =:nombre_agencia'
                )->setParameter('nombre_agencia', $Nombre_Agencia);
        if ($query->getResult()) {
            $Agencia = $query->setMaxResults(1)->getOneOrNullResult();
            $query = $em->createQuery(
                            'SELECT ap
                        FROM AdminAdminBundle:AgenciaPhoto ap
                        WHERE ap.idAgencia  =:id'
                    )->setParameter('id', $Agencia->getId());
            if ($query->getResult()) {
                //la agencia tiene logotipo
                $Agenciaphoto = $query->getResult();
                // Buscamos el array de resultados
                $Agenciaphoto = $query->setMaxResults(1)->getOneOrNullResult();
                $logoId = $Agenciaphoto->getIdPhoto()->getId();
                $logoName = $Agenciaphoto->getIdPhoto()->getImage();
            } else {
                $logoId = null;
                $logoName = null;
            }
        } else {
            //no se encontro agencia
            $Agencia = null;
            $logoId = null;
            $logoName = null;
        }
        return array(
            'entity' => $Agencia,
            'idlogo' => $logoId,
            'idNombre' => $logoName,
            'U_foto_Agencia' => $U_foto_Agencia,
            'U_foto' => $U_foto
        );
    }

    /**
     * @Route("/agencia/", name="agencia_page_send_book")
     * @Method("GET")
     */
    public function AgenciaPageSendBookAction(Request $request) {
        $security_context = $this->get('security.context');
        $em = $this->getDoctrine()->getEntityManager();
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $id = $request->get('id');
        $nombre = $request->get('nombre');
        $nombre = str_replace(' ', '_', $nombre);
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
                            WHERE a.id =:id'
                )->setParameter('id', $id);
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
            } else {
                $entities = $query->getResult();
                foreach ($entities as $entity) {
                    $entity->setActivo('1');
                    $entity->setFechaupdate($date);
                    $em->persist($entity);
                    $em->flush();
                }
            }
        }

        return $this->redirect($this->generateUrl('Agencia_page', array('nombre' => $nombre)));
    }

    /**
     * @Route("/agencias/", name="agencia_page_remove_book")
     * @Method("GET")
     */
    public function AgenciaPageRemoveBookAction(Request $request) {
        $security_context = $this->get('security.context');
        $em = $this->getDoctrine()->getEntityManager();
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $id = $request->get('id');
        $nombre = $request->get('nombre');
        $nombre = str_replace(' ', '_', $nombre);
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
                            WHERE a.id =:id'
                )->setParameter('id', $id);
        $Agencias = $query->getResult();
        $date = new \DateTime('now', new \DateTimeZone('America/Bogota'));
        foreach ($Agencias as $agencia) {
            $query = $em->createQuery(
                            'SELECT ah
                            FROM AdminAdminBundle:AgenciaHojadevida ah
                            WHERE (ah.idAgencia =:idagencia and ah.idHojadevida=:idHojadevida)'
                    )->setParameter('idagencia', $agencia->getId())
                    ->setParameter('idHojadevida', $Hojadevida->getId());
            if ($query->getResult()) {
                $entities = $query->getResult();
                foreach ($entities as $entity) {
                    $entity->setActivo('0');
                    $entity->setFechaupdate($date);
                    $em->persist($entity);
                    $em->flush();
                }
            }
        }

        return $this->redirect($this->generateUrl('Agencia_page', array('nombre' => $nombre)));
    }

    /**
     * consulta a default entity.
     *
     * @Route("/ajax/{id}/talentos", name="Agencia_ajax_talentos")
     * @Method("GET")
     */
    public function ajaxtalentosAction($id) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT AH
                            FROM AdminAdminBundle:AgenciaHojadevida AH
                            WHERE AH.idAgencia  =:idAgencia and AH.Activo =1
                            ORDER BY AH.fechaupdate DESC'
                )->setParameter('idAgencia', $id);
        if ($query->getResult()) {
            $AgenciaHojadevida = $query->getResult();
            foreach ($AgenciaHojadevida as $aux) {
                $ids[] = $aux->getIdHojadevida(); //convertimos la consulta en un array
            }
        } else {
            $ids[] = null;
        }
        $entryQuery = $em->createQueryBuilder()
                ->select('hp', 'p', 'h')
                ->from('AdminAdminBundle:HojadevidaPhoto', 'hp')
                ->leftJoin('hp.idHojadevida', 'h')
                ->leftJoin('hp.idPhoto', 'p')
                ->andWhere('hp.principal =:principal')
                ->andWhere('hp.idHojadevida in (:ids)')
                ->addOrderBy('hp.fechaupdate', 'DESC')
                ->setParameter('principal', '1')
                ->setParameter('ids', $ids);
        $entryQuery->setFirstResult(0)->setMaxResults(6);
        $entryQueryfinal = $entryQuery->getQuery();
        //obtenemos el array de resultados
        $entities = $entryQueryfinal->getArrayResult();
        return $this->render('AdminAgenciaBundle:Page:ajax_talentos.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * consulta a default entity.
     *
     * @Route("/ajax/{id}/solicitudes", name="Agencia_ajax_solicitudes")
     * @Method("GET")
     */
    public function listSolicitudAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entities=$em->getRepository('AdminAgenciaBundle:Solicitud')->findBy(array('idAgencia'=>$id,'privado'=>false,'activo'=>'1'),arraY('fechaupdate'=>'DESC'));
        return $this->render('AdminAgenciaBundle:Page:listSolicitud.html.twig', array(
                    'entities' => $entities,
        ));
    }
    /**
     * consulta a default entity.
     *
     * @Route("/solicitud/{id}", name="Agencia_solicitud")
     * @Method("GET")
     */
    public function SolicitudAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity=$em->getRepository('AdminAgenciaBundle:Solicitud')->find($id);
        $agencia=$em->getRepository('AdminAdminBundle:Agencia')->find($entity->getIdAgencia());
        return $this->render('AdminAgenciaBundle:Page:solicitud.html.twig', array(
                    'entity' => $entity,
            'agencia'=>$agencia,
        ));
    }

}
