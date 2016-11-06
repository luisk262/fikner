<?php

namespace Admin\AgenciaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\Photo;
use Admin\AdminBundle\Entity\AgenciaPhoto;
use Admin\MyaccountBundle\Form\PhotoType;
use DateTime;

/**
 * Photo controller.
 *
 * @Route("/Agencia/dashboard/perfil/photo")
 */
class PhotoController extends Controller {

    
    /**
     * Lists all Photo entities.
     *
     * @Route("/list/{id}", name="Agencia_photo_list")
     * @Method("GET")
     * @Template()
     */
    public function listAction($id) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT HP
                            FROM AdminAdminBundle:HojadevidaPhoto HP
                            WHERE HP.idHojadevida  =:idHojadevida'
                )->setParameter('idHojadevida', $id);
        if ($query->getResult()) {
            $aux = $query->getResult();
            $HojadevidaP = $query->getResult();
            foreach ($HojadevidaP as $aux) {
                $ids[] = $aux->getIdPhoto(); //convertimos la consulta en un array
            }
        } else {
            $ids[] = null;
        }
        $entryQuery = $em->createQueryBuilder()
                ->select('P')
                ->from('AdminAdminBundle:Photo', 'P')
                ->andWhere('P.id in (:ids)')
                ->addOrderBy('P.fechaupdate', 'DESC')
                ->setParameter('ids', $ids);
        $entryQueryfinal = $entryQuery->getQuery();
        //obtenemos el array de resultados
        $entities = $entryQueryfinal->getArrayResult();
        return array(
            'entities' => $entities,
        );
    }
    
    
    /**
     * Creates a new Photo entity.
     *
     * @Route("/", name="Agencia_photo_create")
     * @Method("POST")
     * @Template("AdminAgenciaBundle:Photo:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Photo();
        $agenciaPhoto = new AgenciaPhoto();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
            $security_context = $this->get('security.context');
            //definimos que usuario se encuentra logeado
            $security_token = $security_context->getToken();
            $user = $security_token->getUser();
            $query = $em->createQuery(
                            'SELECT AU
                            FROM AdminAdminBundle:AgenciaUsuario AU
                            WHERE AU.idUsuario  =:idUsuario'
                    )->setParameter('idUsuario', $user->getId());
            //si el usuario tiene agencia continuamos
            if ($query->getResult()) {
                $AUsuario = $query->setMaxResults(1)->getOneOrNullResult();
                /// vamos a buscar si el usuario ya tenia ya tiene otras fotos registradas
                // de lo contrario  subimos la foto pero la colocamos como foto principal
                $queryaux = $em->createQueryBuilder()
                        ->select('COUNT(AP)')
                        ->from('AdminAdminBundle:AgenciaPhoto', 'AP')
                        ->andWhere('AP.idAgencia =:idAgencia')
                        ->andWhere('AP.principal =:principal')
                        ->setParameter('idAgencia', $AUsuario->getIdAgencia())
                        ->setParameter('principal','1');
                $total_Photos = $queryaux->getQuery()->getSingleScalarResult();
                if ($total_Photos < 1) {
                    //necesitamos colocar la foto como foto principal
                    $entity->setFecha($date);
                    $entity->setFechaupdate($date);
                    $em->persist($entity);
                    $em->flush();
                    $agenciaPhoto->setIdAgencia($AUsuario->getIdAgencia());
                    $agenciaPhoto->setIdPhoto($entity);
                    //aqui definimos que sea principal
                    $agenciaPhoto->setPrincipal(1);
                    $agenciaPhoto->setFecha($date);
                    $agenciaPhoto->setFechaupdate($date);
                    $em->persist($agenciaPhoto);
                    $em->flush();
                } else {
                    // no colocamos la foto como principal
                    $entity->setFecha($date);
                    $entity->setFechaupdate($date);
                    $em->persist($entity);
                    $em->flush();
                    $agenciaPhoto->setIdAgencia($AUsuario->getIdAgencia());
                    $agenciaPhoto->setIdPhoto($entity);
                    $agenciaPhoto->setFecha($date);
                    $agenciaPhoto->setFechaupdate($date);
                    $em->persist($agenciaPhoto);
                    $em->flush();
                }
            }
            return $this->redirect($this->generateUrl('agencia_dashboard'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'AgenciaP' => null
        );
    }

    /**
     * Creates a form to create a Photo entity.
     *
     * @param Photo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Photo $entity) {
        $form = $this->createForm(new PhotoType(), $entity, array(
            'action' => $this->generateUrl('Agencia_photo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Subir Imagen','attr'=>array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Photo entity.
     *
     * @Route("/new", name="Agencia_photo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new Photo();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'AgenciaP' => null
        );
    }
/**
     * Finds and displays a Photo entity.
     *
     * @Route("/list/{id}/show", name="Agencia_photo_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:Photo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }
        $deleteForm = $this->createDeleteForm($id);
        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
            'AgenciaP' => null
        );
    }
    
    /**
     * Displays a form to edit an existing Photo entity.
     *
     * @Route("/{id}/edit", name="Agencia_photo_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:Photo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'AgenciaP' => null
        );
    }

    /**
     * Creates a form to edit a Photo entity.
     *
     * @param Photo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Photo $entity) {
        $form = $this->createForm(new PhotoType(), $entity, array(
            'action' => $this->generateUrl('Agencia_photo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar Foto'));

        return $form;
    }

    /**
     * Edits an existing Photo entity.
     *
     * @Route("/{id}", name="Agencia_photo_update")
     * @Method("PUT")
     * @Template("AdminAgenciaBundle:Photo:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT p
        FROM AdminAdminBundle:Photo p
        WHERE p.id  =:id'
                )->setParameter('id', $id);

        $photo = $query->getResult();
        // en esta instruccion sacamos los nombres de las imagenes que estan en la base de datos
        $photo = $query->setMaxResults(1)->getOneOrNullResult();
        $imagen1 = $photo->getImage();

        $entity = $em->getRepository('AdminAdminBundle:Photo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
            $entity->setAux($imagen1);
            $entity->setFechaupdate($date);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('Agencia_photo_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'AgenciaP' => null
        );
    }

    /**
     * Deletes a Photo entity.
     *
     * @Route("/{id}", name="Agencia_photo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $security_context = $this->get('security.context');
            //definimos que usuario se encuentra logeado
            $security_token = $security_context->getToken();
            $user = $security_token->getUser();
            $query = $em->createQuery(
                            'SELECT AU
                            FROM AdminAdminBundle:AgenciaUsuario AU
                            WHERE AU.idUsuario  =:idUsuario'
                    )->setParameter('idUsuario', $user->getId());
                        
//si el usuario tiene Agencia continuamos
            if ($query->getResult()) {
                $AUsario = $query->setMaxResults(1)->getOneOrNullResult();
                $query = $em->createQuery(
                            'SELECT AP
                            FROM AdminAdminBundle:AgenciaPhoto AP
                            WHERE (AP.idAgencia  =:idAgencia) and (AP.idPhoto  =:idPhoto)'
                    )->setParameter('idAgencia', $AUsario->getIdAgencia())
                    ->setParameter('idPhoto', $id);
                $AgenciaPhoto = $query->setMaxResults(1)->getOneOrNullResult();
                $em->remove($AgenciaPhoto);
                $em->flush();
            }
            $entity = $em->getRepository('AdminAdminBundle:Photo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Photo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('agencia_dashboard'));
    }

    /**
     * Creates a form to delete a Photo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('Agencia_photo_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Eliminar','attr'=>array('class'=>'btn btn-danger btn-block')))
                        ->getForm()
        ;
    }

}
