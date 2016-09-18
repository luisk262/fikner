<?php

namespace Admin\AgenciaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\File;
use Admin\AdminBundle\Entity\AgenciaFile;
use Admin\AgenciaBundle\Form\FileType;
use DateTime;

/**
 * File controller.
 *
 * @Route("/Agencia/dashboard/perfil/file")
 */
class FileController extends Controller {
/**
     * Creates a new File entity.
     *
     * @Route("/", name="Agencia_file_create")
     * @Method("POST")
     * @Template("AdminAgenciaBundle:File:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new File();
        $agenciaFile = new AgenciaFile();
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
                        ->select('COUNT(AF)')
                        ->from('AdminAdminBundle:AgenciaFile', 'AF')
                        ->andWhere('AF.idAgencia =:idAgencia')
                        ->andWhere('AF.principal =:principal')
                        ->setParameter('idAgencia', $AUsuario->getIdAgencia())
                        ->setParameter('principal','1');
                $total_File = $queryaux->getQuery()->getSingleScalarResult();
                if ($total_File < 1) {
                    //necesitamos colocar la foto como foto principal
                    $entity->setFecha($date);
                    $entity->setFechaupdate($date);
                    $em->persist($entity);
                    $em->flush();
                    $agenciaFile->setIdAgencia($AUsuario->getIdAgencia());
                    $agenciaFile->setIdFile($entity);
                    //aqui definimos que sea principal
                    $agenciaFile->setPrincipal(1);
                    $agenciaFile->setFecha($date);
                    $agenciaFile->setFechaupdate($date);
                    $em->persist($agenciaFile);
                    $em->flush();
                } else {
                    // no colocamos la foto como principal
                    $entity->setFecha($date);
                    $entity->setFechaupdate($date);
                    $em->persist($entity);
                    $em->flush();
                    $agenciaFile->setIdAgencia($AUsuario->getIdAgencia());
                    $agenciaFile->setIdFile($entity);
                    $agenciaFile->setFecha($date);
                    $agenciaFile->setFechaupdate($date);
                    $em->persist($agenciaFile);
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
     * Creates a form to create a File entity.
     *
     * @param File $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(File $entity) {
        $form = $this->createForm(new FileType(), $entity, array(
            'action' => $this->generateUrl('Agencia_file_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Subir File','attr'=>array('class'=>'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new File entity.
     *
     * @Route("/new", name="Agencia_file_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new File();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'AgenciaP' => null
        );
    }

    /**
     * Finds and displays a File entity.
     *
     * @Route("/{id}", name="Agencia_file_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find file entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
            'AgenciaP' => null
        );
    }

    /**
     * Displays a form to edit an existing File entity.
     *
     * @Route("/{id}/edit", name="Agencia_file_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
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
     * Creates a form to edit a file entity.
     *
     * @param File $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(File $entity) {
        $form = $this->createForm(new FileType(), $entity, array(
            'action' => $this->generateUrl('Agencia_file_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar File'));

        return $form;
    }

    /**
     * Edits an existing File entity.
     *
     * @Route("/{id}", name="Agencia_file_update")
     * @Method("PUT")
     * @Template("AdminAgenciaBundle:File:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT p
        FROM AdminAdminBundle:File p
        WHERE p.id  =:id'
                )->setParameter('id', $id);

        $file = $query->getResult();
        // en esta instruccion sacamos los nombres de las imagenes que estan en la base de datos
        $file = $query->setMaxResults(1)->getOneOrNullResult();
        $file1 = $file->getFile();

        $entity = $em->getRepository('AdminAdminBundle:File')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find File entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
            $entity->setAux($file1);
            $entity->setFechaupdate($date);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('Agencia_file_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'AgenciaP' => null
        );
    }

    /**
     * Deletes a File entity.
     *
     * @Route("/{id}", name="Agencia_file_delete")
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
                            'SELECT AF
                            FROM AdminAdminBundle:AgenciaFile AF
                            WHERE (AF.idAgencia  =:idAgencia) and (AF.idFile  =:idFile)'
                    )->setParameter('idAgencia', $AUsario->getIdAgencia())
                    ->setParameter('idFile', $id);
                $AgenciaFile = $query->setMaxResults(1)->getOneOrNullResult();
                $em->remove($AgenciaFile);
                $em->flush();
            }
            $entity = $em->getRepository('AdminAdminBundle:File')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find File entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('agencia_dashboard'));
    }

    /**
     * Creates a form to delete a File entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('Agencia_file_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Eliminar','attr'=>array('class'=>'btn btn-danger btn-block')))
                        ->getForm()
        ;
    }

}
