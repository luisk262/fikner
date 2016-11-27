<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Admin\AdminBundle\Entity\Hojadevida;
use Admin\AdminBundle\Entity\AgenciaHojadevida;
use Admin\AdminBundle\Form\HojadevidaType;
use Admin\AdminBundle\Pagination\Paginator;
use DateTime;

/**
 * Hojadevida controller.
 *
 * @Route("/dashboard/hojadevida")
 */
class HojadevidaController extends Controller {

    /**
     * Lists all Hojadevida entities.
     *
     * @Route("/", name="dashboard_hojadevida")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $request = $this->getRequest();
        //Asignamos el parametro url para luego pasarlo a ajax
        $page = $request->query->get('page');
        $searchParam = $request->get('searchParam');
        //$em = $this->getDoctrine()->getManager();
        //$entities = $em->getRepository('AdminAdminBundle:Hojadevida')->findAll();

        return array(
            'current_page' => $page,
            'searchParam' => $searchParam
        );
    }

    /**
     * consulta a Hojadevida entity.
     *
     * @Route("/ajax/consulta", name="hojadevida_ajax")
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

        if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            $entryQuery = $em->createQueryBuilder()
                    ->select('hp', 'p', 'h')
                    ->from('AdminAdminBundle:HojadevidaPhoto', 'hp')
                    ->leftJoin('hp.idHojadevida', 'h')
                    ->leftJoin('hp.idPhoto', 'p')
                    ->andWhere('hp.principal =:principal')
                    ->addOrderBy('hp.fechaupdate', 'DESC')
                    ->setParameter('principal', '1');
            //query aux
            $queryaux = $em->createQueryBuilder()
                    ->select('COUNT(hp)')
                    ->from('AdminAdminBundle:HojadevidaPhoto', 'hp')
                    ->leftJoin('hp.idHojadevida', 'h')
                    ->leftJoin('hp.idPhoto', 'p')
                    ->andWhere('hp.principal =:principal')
                    ->addOrderBy('hp.fechaupdate', 'DESC')
                    ->setParameter('principal', '1');
        } else {
            $security_token = $security_context->getToken();
            //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
            $user = $security_token->getUser();
            //buscamos solicitudes generadas por usuario 
            $query = $em->createQuery(
                            'SELECT AU
                            FROM AdminAdminBundle:AgenciaUsuario AU
                            WHERE AU.idUsuario  =:idUsuario'
                    )->setParameter('idUsuario', $user->getId());

            if ($query->getResult()) {
                $agenciaU = $query->setMaxResults(1)->getOneOrNullResult();
                $query = $em->createQuery(
                                'SELECT AH
                            FROM AdminAdminBundle:AgenciaHojadevida AH
                            WHERE AH.idAgencia  =:idAgencia'
                        )->setParameter('idAgencia', $agenciaU->getIdAgencia());
                if ($query->getResult()) {
                    $aux = $query->getResult();
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
                        ->andWhere('h.id in (:ids)')
                        ->addOrderBy('hp.fechaupdate', 'DESC')
                        ->setParameter('principal', '1')
                        ->setParameter('ids', $ids);
                //query aux
                $queryaux = $em->createQueryBuilder()
                        ->select('hp', 'p', 'h')
                        ->from('AdminAdminBundle:HojadevidaPhoto', 'hp')
                        ->leftJoin('hp.idHojadevida', 'h')
                        ->leftJoin('hp.idPhoto', 'p')
                        ->andWhere('hp.principal =:principal')
                        ->andWhere('h.id in (:ids)')
                        ->addOrderBy('hp.fechaupdate', 'DESC')
                        ->setParameter('principal', '1')
                        ->setParameter('ids', $ids);
            }
        }
        if (!empty($general)) {
            $entryQuery->andWhere(''
                            . 'h.nombre Like :general or '
                            . 'h.apellido Like :general or '
                            . 'h.telCasa Like :general or '
                            . 'h.telCe Like :general or '
                            . 'h.telefonoAdic Like :general or '
                            . 'h.tipoDocumento Like :general or '
                            . 'h.nit Like :general or '
                            . 'h.ciudadresidencia Like :general or '
                            . 'h.emailPersonal Like :general or '
                            . 'h.piel Like :general or '
                            . 'h.ojos Like :general or '
                            . 'h.pelo Like :general or '
                            . 'h.peso Like :general or '
                            . 'h.deportes Like :general or '
                            . 'h.habilidades Like :general or '
                            . 'h.idiomas Like :general or '
                            . 'h.maneja Like :general or '
                            . 'h.tallaCamisa Like :general or '
                            . 'h.tallaChaqueta Like :general or '
                            . 'h.tallaPantalon Like :general or '
                            . 'h.tallaZapato Like :general or '
                            . 'h.estatura Like :general')
                    ->setParameter('general', '%' . $general . '%');
            $queryaux->andWhere(''
                            . 'h.nombre Like :general or '
                            . 'h.apellido Like :general or '
                            . 'h.telCasa Like :general or '
                            . 'h.telCe Like :general or '
                            . 'h.telefonoAdic Like :general or '
                            . 'h.tipoDocumento Like :general or '
                            . 'h.nit Like :general or '
                            . 'h.ciudadresidencia Like :general or '
                            . 'h.emailPersonal Like :general or '
                            . 'h.piel Like :general or '
                            . 'h.ojos Like :general or '
                            . 'h.pelo Like :general or '
                            . 'h.peso Like :general or '
                            . 'h.deportes Like :general or '
                            . 'h.habilidades Like :general or '
                            . 'h.idiomas Like :general or '
                            . 'h.maneja Like :general or '
                            . 'h.tallaCamisa Like :general or '
                            . 'h.tallaChaqueta Like :general or '
                            . 'h.tallaPantalon Like :general or '
                            . 'h.tallaZapato Like :general or '
                            . 'h.estatura Like :general')
                    ->setParameter('general', '%' . $general . '%');
        }


        if (!empty($id)) {
            $entryQuery->andWhere('hp.id =:id ')->setParameter('id', $id);
            $queryaux->andWhere('hp.id =:id ')->setParameter('id', $id);
        }
        if (!empty($ciudadresidencia)) {
            $entryQuery->andWhere('h.ciudadresidencia =:ciudadresidencia ')->setParameter('ciudadresidencia', $ciudadresidencia);
            $queryaux->andWhere('h.ciudadresidencia =:ciudadresidencia ')->setParameter('ciudadresidencia', $ciudadresidencia);
        }
        if (!empty($telCe)) {
            $entryQuery->andWhere('h.telCe =:telCe ')->setParameter('telCe', $telCe);
            $queryaux->andWhere('h.telCe =:telCe ')->setParameter('telCe', $telCe);
        }
        if (!empty($EdadMin) && !empty($EdadMax)) {
            $date = new \DateTime('now');
            if ($EdadMin > $EdadMax) {
                $aux = $EdadMin;
                $EdadMin = $EdadMax;
                $EdadMax = $aux;
            }
            $entryQuery->andWhere("DATE_FORMAT(h.fechaNac,'%Y-%m-%d') = :EdadMin")
                    ->setParameter('EdadMin', '1998-03-03')
            ;
            $queryaux->andWhere('h.fechaNac = :EdadMin')
                    ->setParameter('EdadMin', '1998-03-03');
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
            $entryQuery->andWhere('h.Tags Like :Tags')->setParameter('Tags', '%' . $Tags . '%');
            $queryaux->andWhere('h.Tags Like :Tags')->setParameter('Tags', '%' . $Tags . '%');
        }

        $total_count = $queryaux->getQuery()->getSingleScalarResult();

        if (!empty($perPage))
            $entryQuery->setFirstResult(($page - 1) * $perPage)->setMaxResults($perPage);
        $entryQueryfinal = $entryQuery->getQuery();
        //obtenemos el array de resultados
        $entities = $entryQueryfinal->getArrayResult();
        $pagination = (new Paginator())->setItems($total_count, $searchParam['perPage'])->setPage($searchParam['page'])->toArray();
        //renderizamos la vista para mostrar las hojas de vida
        return $this->render('AdminAdminBundle:Hojadevida:ajax_list.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new Hojadevida entity.
     *
     * @Route("/", name="dashboard_hojadevida_create")
     * @Method("POST")
     * @Template("AdminAdminBundle:Hojadevida:new.html.twig")
     */
    public function createAction(Request $request) {
        //inicializamos hoja de vida
        $entity = new Hojadevida();
        //inicializamos agenciaHojadevida
        $agenciahojadevida = new AgenciaHojadevida();
        ///creamos el formulario segun la entidad
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            //creamos una conexion a la base de datos
            $em = $this->getDoctrine()->getEntityManager();
            //realizamos un query sencillo para verificar que no se encuentre registrada la hoja de vida
            $query = $em->createQuery(
                    'SELECT h.nit
            FROM AdminAdminBundle:Hojadevida h where h.nit=' . $entity->getNit() . ''
            );
            //adquirimos el array
            if ($query->getResult()) {
                return $this->redirect($this->generateUrl('dashboard_hojadevida_new'));
            } else {
                switch ($entity->getEstado()) {
                    case 'Activo':
                        ///notificamos al usuario que ha sido activado, enviandole un correo electronico
                        $names = $entity->getNombre() . ' ' . $entity->getApellido();
                        $email = $entity->getEmailPersonal();
                        $Subject = 'Bienvenido ';
                        $Body = 'Bienvenido '
                                . '.ESTE CORREO ES AUTOMATICO, NO RESPONDA ESTE EMAIL';
                        $correo = 'luisk__@hotmil.com';
                        $message = \Swift_Message::newInstance()
                                ->setSubject($Subject)
                                ->setFrom($correo)
                                ->setTo($email)
                                ->setBody(
                                <<<EOF
                Nombres: $names
                Asunto: $Subject
                Correo: $email
                
                $Body
EOF
                                )
                        ;
                        $this->get('mailer')->send($message);
                        break;
                }
                $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
                $entity->setFecha($date);
                $entity->setFechaupdate($date);
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                //verificamos que usuario se encuentra logeado
                $user = $this->get('security.context')->getToken()->getUser();
                if (!$this->get('security.context')->isGranted('ROLE_SU_ADMIN')) {
                    //verificamos que usuario se encuentra logeado
                    $security_context = $this->get('security.context');
                    $security_token = $security_context->getToken();
                    $user = $security_token->getUser();
                    //buscamos la agencia asignada al usuario
                    $query = $em->createQuery(
                            'SELECT a
            FROM AdminAdminBundle:AgenciaUsuario a where a.idUsuario=' . $user->getId() . ''
                    );
                    if ($query->getResult()) {
                        $agenciaU = $query->setMaxResults(1)->getOneOrNullResult();
                        $agenciahojadevida->setIdAgencia($agenciaU->getIdAgencia());
                        $agenciahojadevida->setIdHojadevida($entity);
                        //guardamos en base de datos
                        $em->persist($agenciahojadevida);
                        $em->flush();
                    }
                }


                return $this->redirect($this->generateUrl('dashboard_hojadevida_show', array('id' => $entity->getId())));
            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Hojadevida entity.
     *
     * @param Hojadevida $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Hojadevida $entity) {
        $form = $this->createForm(new HojadevidaType(), $entity, array(
            'action' => $this->generateUrl('dashboard_hojadevida_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/new", name="dashboard_hojadevida_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new Hojadevida();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Hojadevida entity.
     *
     * @Route("/{id}", name="dashboard_hojadevida_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminAdminBundle:Hojadevida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hojadevida entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Hojadevida entity.
     *
     * @Route("/{id}/edit", name="dashboard_hojadevida_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $entity = $em->getRepository('AdminAdminBundle:Hojadevida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hojadevida entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Hojadevida entity.
     *
     * @param Hojadevida $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Hojadevida $entity) {
        $form = $this->createForm(new HojadevidaType(), $entity, array(
            'action' => $this->generateUrl('dashboard_hojadevida_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Hojadevida entity.
     *
     * @Route("/{id}", name="dashboard_hojadevida_update")
     * @Method("PUT")
     * @Template("AdminAdminBundle:Hojadevida:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminAdminBundle:Hojadevida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hojadevida entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
            $entity->setFechaupdate($date);
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('dashboard_hojadevida_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView()
        );
    }

    /**
     * Deletes a Hojadevida entity.
     *
     * @Route("/{id}", name="dashboard_hojadevida_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminAdminBundle:Hojadevida')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Hojadevida entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dashboard_hojadevida'));
    }

    /**
     * Creates a form to delete a Hojadevida entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('dashboard_hojadevida_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    /**
     * Finds and displays a Hojadevida entity.
     *
     * @Route("/fullimage/{nit}/{id}/{foto}", name="dashboard_hojadevida_showfoto")
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

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/ajax/remove", name="dashboard_hojadevida_remove")
     * @Method("GET")
     * @Template()
     */
    public function removeAction(Request $request) {
        $em = $this->getDoctrine()->getEntityManager();

        $ids = $request->get('entities');

        $entryQuery = $em->createQueryBuilder()
                ->select('c')
                ->from('AdminAdminBundle:Hojadevida', 'c');
        if (!empty($ids)) {
            $entryQuery->andWhere('c.id in (:ids)')->setParameter('ids', $ids);
        }
        $entryQueryfinal = $entryQuery->getQuery();
        //obtenemos el array de resultados
        $entities = $entryQueryfinal->getResult();


        foreach ($entities as $entity)
            $em->remove($entity);
        $em->flush();
        return new Response('1');
    }

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/ajax/mail", name="dashboard_hojadevida_mail")
     * @Method("GET")
     * @Template()
     */
    public function enviaremailAction(Request $request) {
        $em = $this->getDoctrine()->getEntityManager();
        $ids = $request->get('entities');
        //Asignamos el parametro url
        $mail = $request->get('mail');
        //extraemos variables del array
        extract($mail);
        $data['titulo2']=$Subject;
        $Subject = 'Fikner - ' . $Subject;
        $entryQuery = $em->createQueryBuilder()
                ->select('c')
                ->from('AdminAdminBundle:Hojadevida', 'c');
        if (!empty($ids)) {
            $entryQuery->andWhere('c.id in (:ids)')->setParameter('ids', $ids);
            $entryQueryfinal = $entryQuery->getQuery();
            //obtenemos el array de resultados
            $entities = $entryQueryfinal->getResult();
            $correo_remitente = 'youfikner@gmail.com';
            $data['titulo1']='Tenemos una noticia nueva';            
            $data['body']=$Body;
            $data['firma']='';                    
            foreach ($entities as $entity) {
                $template=$this->renderView('AdminAdminBundle:views:email.html.twig',array('data'=>$data));
                $email = $entity->getEmailPersonal();
                $message = \Swift_Message::newInstance()
                        ->setSubject($Subject)
                        ->setFrom($correo_remitente)
                        ->setTo($email)
                        ->setBody($template,'text/html')
                ;
                $this->get('mailer')->send($message);
            }
        }

        return new Response('1');
    }
    
}
