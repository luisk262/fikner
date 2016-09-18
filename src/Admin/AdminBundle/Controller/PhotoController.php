<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
        return array(
            'entity' => $entity,
        );
    }

}
