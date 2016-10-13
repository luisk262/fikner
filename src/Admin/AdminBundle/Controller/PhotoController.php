<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\Photo;
use Admin\AdminBundle\Entity\HojadevidaPhoto;
use Admin\MyaccountBundle\Form\PhotoType;

/**
 * Photo controller.
 *
 * @Route("/dashboard/hojadevida/photo")
 */
class PhotoController extends Controller {

    /**
     * Lists all Photo entities.
     *
     * @Route("/list/{id}", name="Admin_photo_list")
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
     * Finds and displays a Photo entity.
     *
     * @Route("/list/{id}/show", name="Admin_photo_show")
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
        );
    }
    /**
     * Deletes a Photo entity.
     *
     * @Route("/{id}", name="Admin_photo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //definimos que usuario se encuentra logeado
            $security_token = $security_context->getToken();
            $user = $security_token->getUser();
            $query = $em->createQuery(
                            'SELECT UH
                            FROM AdminAdminBundle:UsuarioHojadevida UH
                            WHERE UH.idUsuario  =:idUsuario'
                    )->setParameter('idUsuario', $user->getId());

//si el usuario tiene hoja de vida continuamos
            if ($query->getResult()) {
                $UHojadevida = $query->setMaxResults(1)->getOneOrNullResult();
                $query = $em->createQuery(
                                'SELECT HP
                            FROM AdminAdminBundle:HojadevidaPhoto HP
                            WHERE (HP.idHojadevida  =:idHojadevida) and (HP.idPhoto  =:idPhoto)'
                        )->setParameter('idHojadevida', $UHojadevida->getIdHojadevida())
                        ->setParameter('idPhoto', $id);
                $HojadevidaPhoto = $query->setMaxResults(1)->getOneOrNullResult();
                print "entramos";

                $em->remove($HojadevidaPhoto);
                $em->flush();
            }
            $entity = $em->getRepository('AdminAdminBundle:Photo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Photo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('Myaccount_photo'));
    }
     /**
     * Creates a form to delete a Photo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('Admin_photo_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Eliminar', 'attr' => array('class' => 'btn btn-danger btn-block')))
                        ->getForm()
        ;
    }
      /**
     * Creates a new Photo entity.
     *
     * @Route("/", name="Admin_photo_create")
     * @Method("POST")
     * @Template("AdminAdminBundle:Photo:new.html.twig")
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $security_context = $this->get('security.context');
        //definimos que usuario se encuentra logeado
        $security_token = $security_context->getToken();
        $user = $security_token->getUser();
        $query = $em->createQuery(
                        'SELECT UH
                            FROM AdminAdminBundle:UsuarioHojadevida UH
                            WHERE UH.idUsuario  =:idUsuario'
                )->setParameter('idUsuario', $user->getId());
        //si el usuario tiene hoja de vida continuamos
        if ($query->getResult()) {
            $UHojadevida = $query->setMaxResults(1)->getOneOrNullResult();
            $queryaux = $em->createQueryBuilder()
                    ->select('COUNT(HP)')
                    ->from('AdminAdminBundle:HojadevidaPhoto', 'HP')
                    ->andWhere('HP.idHojadevida =:idHojadevida')
                    ->setParameter('idHojadevida', $UHojadevida->getIdHojadevida());
            $total_Photos = $queryaux->getQuery()->getSingleScalarResult();
            if ($total_Photos >= 6) {
                return $this->redirect($this->generateUrl('Myaccount_photo'));
            }
        }
        $entity = new Photo();
        $hojadevidaPhoto = new HojadevidaPhoto();
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
                            'SELECT UH
                            FROM AdminAdminBundle:UsuarioHojadevida UH
                            WHERE UH.idUsuario  =:idUsuario'
                    )->setParameter('idUsuario', $user->getId());
            //si el usuario tiene hoja de vida continuamos
            if ($query->getResult()) {
                $UHojadevida = $query->setMaxResults(1)->getOneOrNullResult();
                /// vamos a buscar si el usuario ya tenia ya tiene otras fotos registradas
                // de lo contrario  subimos la foto pero la colocamos como foto principal
                $queryaux = $em->createQueryBuilder()
                        ->select('COUNT(HP)')
                        ->from('AdminAdminBundle:HojadevidaPhoto', 'HP')
                        ->andWhere('HP.idHojadevida =:idHojadevida')
                        ->andWhere('HP.principal =:principal')
                        ->setParameter('idHojadevida', $UHojadevida->getIdHojadevida())
                        ->setParameter('principal', '1');
                $total_Photos = $queryaux->getQuery()->getSingleScalarResult();
                if ($total_Photos < 1) {
                    //necesitamos colocar la foto como foto principal
                    $entity->setFecha($date);
                    $entity->setFechaupdate($date);
                    $em->persist($entity);
                    $em->flush();
                    $hojadevidaPhoto->setIdHojadevida($UHojadevida->getIdHojadevida());
                    $hojadevidaPhoto->setIdPhoto($entity);
                    //aqui definimos que sea principal
                    $hojadevidaPhoto->setPrincipal(1);
                    $hojadevidaPhoto->setFecha($date);
                    $hojadevidaPhoto->setFechaupdate($date);
                    $em->persist($hojadevidaPhoto);
                    $em->flush();
                } else {
                    // no colocamos la foto como principal
                    $entity->setFecha($date);
                    $entity->setFechaupdate($date);
                    $em->persist($entity);
                    $em->flush();
                    $hojadevidaPhoto->setIdHojadevida($UHojadevida->getIdHojadevida());
                    $hojadevidaPhoto->setIdPhoto($entity);
                    $hojadevidaPhoto->setFecha($date);
                    $hojadevidaPhoto->setFechaupdate($date);
                    $em->persist($hojadevidaPhoto);
                    $em->flush();
                }
            }
            return $this->redirect($this->generateUrl('Myaccount_photo'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
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
            'action' => $this->generateUrl('Admin_photo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Subir Imagen', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Photo entity.
     *
     * @Route("/{id}/new", name="Admin_photo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id) {
        
        $entity = new Photo();
        
        $form = $this->createCreateForm($entity);
        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

}
