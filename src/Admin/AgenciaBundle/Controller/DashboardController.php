<?php

namespace Admin\AgenciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 *
 * @Route("/Agencia/dashboard")
 */
class DashboardController extends Controller {

    /**
     * @Route("/", name="agencia_dashboard")
     * @Template()
     */
    public function indexAction() {
        $this->get('validar.path')->formulariousuario();//redireccionamos al formulario correspondiente
        $security_context = $this->get('security.context');
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT au
                        FROM AdminAdminBundle:AgenciaUsuario au
                        WHERE au.idUsuario  =:id'
                )->setParameter('id', $user->getId());
        if ($query->getResult()) {
            $Agencia = $query->getResult();
            // Buscamos el array de resultados
            $Agencia = $query->setMaxResults(1)->getOneOrNullResult();
            //verificamos si la agencia tiene logotipo
            $query = $em->createQuery(
                            'SELECT ap
                        FROM AdminAdminBundle:AgenciaPhoto ap
                        WHERE ap.idAgencia  =:id'
                    )->setParameter('id', $Agencia->getIdAgencia()->getId());
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
//verificamos el tipo de plan que tiene la agencia//
            $Agenciaplan =$em->getRepository('AdminAdminBundle:Agencia')->plan($Agencia->getIdAgencia()->getId());
//verificamos si la agencia tiene Rut o camara de comercio
            $query = $em->createQuery(
                            'SELECT af
                        FROM AdminAdminBundle:AgenciaFile af
                        WHERE af.idAgencia  =:id'
                    )->setParameter('id', $Agencia->getIdAgencia()->getId());
            if ($query->getResult()) {
                //la agencia tiene logotipo
                $AgenciaFile = $query->getResult();
                // Buscamos el array de resultados
                $AgenciaFile = $query->setMaxResults(1)->getOneOrNullResult();
                $fileId = $AgenciaFile->getIdFile()->getId();
                $fileName = $AgenciaFile->getIdFile()->getFile();
            } else {
                $fileId = null;
                $fileName = null;
            }
            $queryaux = $em->createQueryBuilder()
                    ->select('COUNT(ah)')
                    ->from('AdminAdminBundle:AgenciaHojadevida', 'ah')
                    ->andWhere('ah.idAgencia =:id')
                    ->andWhere('ah.Activo = 1')
                    ->setParameter('id', $Agencia->getIdAgencia()->getId());
            $queryaux->andWhere('ah.Estado =:Estado')->setParameter('Estado', 'Activo');
            $totalbooksA = $queryaux->getQuery()->getSingleScalarResult();
            $queryaux->andWhere('ah.Estado =:Estado')->setParameter('Estado', 'Inactivo');
            $totalbooksR = $queryaux->getQuery()->getSingleScalarResult();
            $queryaux->andWhere('ah.Estado =:Estado')->setParameter('Estado', 'Vetado');
            $totalbooksV = $queryaux->getQuery()->getSingleScalarResult();
            $queryaux->andWhere('ah.Estado =:Estado')->setParameter('Estado', 'Pendiente');
            $totalbooksP = $queryaux->getQuery()->getSingleScalarResult();
//definimos el nombre para la ruta de fikner-page
            $ruta = str_replace(' ', '_', $Agencia->getIdAgencia()->getNombreAgencia());
            return array(
                'agencia' => true,
                'nombreagencia' => $Agencia->getIdAgencia()->getNombreAgencia(),
                'ruta' => $ruta,
                'pais' => $Agencia->getIdAgencia()->getPais(),
                'telefono' => $Agencia->getIdAgencia()->getTelefono(),
                'categoria' => $Agencia->getIdAgencia()->getCategoria(),
                'estado' => $Agencia->getIdAgencia()->getActivo(),
                'email' => $Agencia->getIdAgencia()->getEmail(),
                'descripcion' => $Agencia->getIdAgencia()->getDescripcion(),
                'direccion' => $Agencia->getIdAgencia()->getDireccion(),
                'nit' => $Agencia->getIdAgencia()->getNit(),
                'fileId' => $fileId,
                'fileName' => $fileName,
                'logoId' => $logoId,
                'logoName' => $logoName,
                'totalbooksA' => $totalbooksA,
                'totalbooksR' => $totalbooksR,
                'totalbooksP' => $totalbooksP,
                'totalbooksV' => $totalbooksV,
                'AgenciaP' => $Agenciaplan,
            );
        } else {
            //no existe agencia para ese usuario
            return array(
                'agencia' => false,
                'estado' => null,
                'logoId' => null,
                'fileId' => null,
                'AgenciaP' => null,
            );
        }
    }
    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/EmpresasOfertar", name="Myaccount_empresas_Ofertar")
     * @Method("GET")
     */
    public function empresasOfertarAction(Request $request) {
        $security_context = $this->get('security.context');
        $em = $this->getDoctrine()->getManager();
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
        $date = new \DateTime('now',    new \DateTimeZone('America/Bogota'));
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
