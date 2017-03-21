<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Admin\AdminBundle\Entity\User;
use Admin\MyaccountBundle\Form\RegistrationType;
use Admin\AdminBundle\Pagination\Paginator;

class DefaultController extends Controller {

    /**
     * @Route("/", name="principal")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $security_context = $this->get('security.context');
        if (($this->get('security.context')->isGranted('ROLE_RECLU'))) {
            return $this->redirect($this->generateUrl('reclutador_dashboard'));
        }
        if ($this->get('security.context')->isGranted('ROLE_AGENC')) {
            return $this->redirect($this->generateUrl('agencia_dashboard'));
        }
        if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirect($this->generateUrl('admin_dashboard'));
        }
        if (($this->get('security.context')->isGranted('ROLE_USER'))) {
            return $this->redirect($this->generateUrl('Myaccount'));
        }
        $em = $this->getDoctrine()->getManager();
        $AgenciasA = count($em->getRepository('AdminAdminBundle:Agencia')->count());
        $Users =  count($em->getRepository('AdminAdminBundle:User')->count());
        $castings = $em->getRepository('AdminAgenciaBundle:Solicitud')->countAll();
        $entity = new User();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'agencias'=>$AgenciasA,
            'perfiles'=>$Users,
            'castings'=>$castings,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity) {
        $form = $this->createForm(new RegistrationType(), $entity, array(
            'action' => $this->generateUrl('default_registration_create'),
            'method' => 'POST',
        ));
        $form->add('nombre', 'text', array('label' => 'Nombres*', 'max_length' => 30));
        $form->add('apellidos', 'text', array('label' => 'Apellidos*', 'max_length' => 30));
        $form->add('telefono', 'text', array('max_length' => 13, 'label' => 'Telefono*'));
        $form->add('fechanaci', 'date', array(
            'years' => range(date('Y') - 7, date('Y') - 95),
            'required' => True,
            'label' => 'Fecha de nacimimiento',
            'empty_value' => array('year' => 'Año', 'month' => 'Mes', 'day' => 'Dia'),
        ));
        $form->add('email', 'repeated', array(
            'type' => 'email',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'invalid_message' => 'Los dos correos no coinciden',
            'first_options' => array('label' => 'Email', 'attr' => array('placeholder' => 'Email')),
            'second_options' => array('label' => 'Repita Email', 'attr' => array('placeholder' => 'Confirme Email')),
        ));
        $form->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'invalid_message' => 'fos_user.password.mismatch',
            'first_options' => array('label' => 'Contraseña', 'attr' => array('placeholder' => 'Contraseña')),
            'second_options' => array('label' => 'Confirme Contraseña', 'attr' => array('placeholder' => 'Confirme Contraseña'))
        ));
        $form->add('submit', 'submit', array('label' => '¡CREAR CUENTA!'));

        return $form;
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/", name="default_registration_create")
     * @Method("POST")
     * @Template("AdminAdminBundle:Default:index.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //verificamos si el usuario existe o ya estaba registrado
            $user_exist = $em->getRepository('AdminAdminBundle:User')->findByUsername($entity->getEmail());
            if ($user_exist) {
                return $this->redirect(
                                $this->generateUrl('registration_msg', array('id' => $entity->getId(),
                                    'nombre' => $entity->getNombre(),
                                    'apellidos' => $entity->getApellidos(),
                                    'error' => true,
                                    'email' => $entity->getEmail()
                )));
            } else {
                $entity->setUsername($entity->getEmail());
                $entity->setEnabled(true);
                $em->persist($entity);
                $em->flush();
                return $this->redirect(
                                $this->generateUrl('registration_msg', array('id' => $entity->getId(),
                                    'nombre' => $entity->getNombre(),
                                    'apellidos' => $entity->getApellidos(),
                                    'error' => false,
                                    'email' => $entity->getEmail()
                )));
            }
        }
        //eliminamos variables de memoria
        unset($user_exist,$em);
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     *
     * @Route("/dashboard", name="admin_dashboard")
     * @Template()
     */
    public function dashboardAction() {
        $em = $this->getDoctrine()->getManager();
        if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            ////personal de apoyo
            $Books =  count($em->getRepository('AdminAdminBundle:UsuarioHojadevida')->count());
            $Users =  count($em->getRepository('AdminAdminBundle:User')->count());
            $AgenciasA = count($em->getRepository('AdminAdminBundle:Agencia')->count());
            $AgenciasP = count($em->getRepository('AdminAdminBundle:Agencia')->count(0));
            $queryAgenciaU = $em->createQueryBuilder()
                            ->select('COUNT(c)')
                            ->from('AdminAdminBundle:User', 'c')
                            ->andWhere('c.roles  LIKE :roles')->setParameter('roles', '%' . 'ROLE_AGENC' . '%');

            ///personal de apoyo
            $CSinBook = $Users - $Books;
            ///agencias
            $UserSinAgencia = $queryAgenciaU->getQuery()->getSingleScalarResult();
            return $this->render('AdminAdminBundle:Default:dashboard.html.twig', array(
                        'Books' => $Books,
                        'CSinBook' => $CSinBook,
                        'Users' => $Users,
                        'Agencias' => $AgenciasA,
                        'AgenciasT' => $AgenciasP,
                        'AsinUser' => $UserSinAgencia
            ));
        } else {
            ///vamos a buscar la agencia a la cual pertenece el usuario
            $security_context = $this->get('security.context');
            $security_token = $security_context->getToken();
            $user = $security_token->getUser();
            $query = $em->createQuery(
                    'SELECT a
            FROM AdminAdminBundle:AgenciaUsuario a where a.idUsuario=' . $user->getId() . ''
            );
            if ($query->getResult()) {
                ///si el usuario tiene agencia procedemos
                $agenciaU = $query->setMaxResults(1)->getOneOrNullResult();
                //contamos las hojas de vida de la agencia
                $queryAgenciaBT = $em->createQueryBuilder()
                        ->select('COUNT(c)')
                        ->from('AdminAdminBundle:AgenciaHojadevida', 'c');
                $queryAgenciaBT->andWhere('c.idAgencia =:id')->setParameter('id', $agenciaU->getIdAgencia());
                $queryAgenciaBT->andWhere('c.Estado =:estado')->setParameter('estado', 'Activo');
                $BooksA = $queryAgenciaBT->getQuery()->getSingleScalarResult();
                $queryAgenciaBT->andWhere('c.Estado =:estado')->setParameter('estado', 'Inactivo');
                $BooksI = $queryAgenciaBT->getQuery()->getSingleScalarResult();
                $queryAgenciaBT->andWhere('c.Estado =:estado')->setParameter('estado', 'Vetado');
                $BooksV = $queryAgenciaBT->getQuery()->getSingleScalarResult();
            }

            return $this->render('AdminAdminBundle:Default:dashboard.html.twig', array(
                        'BooksI' => $BooksI,
                        'BooksA' => $BooksA,
                        'BooksV' => $BooksV
            ));
        }
    }

    /**
     * consulta a default entity.
     *
     * @Route("/ajax/talentos", name="default_ajax_talentos")
     * @Method("GET")
     */
    public function ajaxtalentosAction() {
        $em = $this->getDoctrine()->getManager();
        $entryQuery = $em->createQueryBuilder()
                ->select('hp', 'p', 'h')
                ->from('AdminAdminBundle:HojadevidaPhoto', 'hp')
                ->leftJoin('hp.idHojadevida', 'h')
                ->leftJoin('hp.idPhoto', 'p')
                ->andWhere('hp.principal =:principal')
                ->addOrderBy('h.Calificacion', 'DESC')
                ->setParameter('principal', '1');
        $entryQuery->setFirstResult(0)->setMaxResults(3);
        $entryQueryfinal = $entryQuery->getQuery();
        //obtenemos el array de resultados
        $entities = $entryQueryfinal->getArrayResult();
        return $this->render('AdminAdminBundle:Default:ajax_talentos.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * consulta a default entity.
     *
     * @Route("/agencia/", name="agencias")
     * @Method("GET")
     * @Template()
     */
    public function showAgenciasAction() {
        $em = $this->getDoctrine()->getManager();
        $entryQuery = $em->createQueryBuilder()
                ->select('ap', 'p', 'a')
                ->from('AdminAdminBundle:AgenciaPhoto', 'ap')
                ->leftJoin('ap.idAgencia', 'a')
                ->leftJoin('ap.idPhoto', 'p')
                ->andWhere('ap.principal =:principal')
                ->andWhere('a.privado =:privado')
                ->andWhere('a.Activo =:activo')
                ->setParameter('principal', '1')
                ->setParameter('activo', '1')
                ->setParameter('privado', '0');
        $entryQueryfinal = $entryQuery->getQuery();
        //obtenemos el array de resultados
        $entities = $entryQueryfinal->getArrayResult();
        return array(
            'entities' => $entities
        );
    }

    /**
     * consulta a default entity.
     *
     * @Route("/ajax/agencias", name="default_ajax_agencias")
     * @Method("GET")
     */
    public function ajaxagenciasAction() {
        
    }

    public function formulariousuario() {
        $security_context = $this->get('security.context');
        if (($this->get('security.context')->isGranted('ROLE_RECLU'))) {
            return $this->redirect($this->generateUrl('reclutador_dashboard'));
        }
        if ($this->get('security.context')->isGranted('ROLE_AGENC')) {
            return $this->redirect($this->generateUrl('agencia_dashboard'));
        }
        if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirect($this->generateUrl('admin_dashboard'));
        }
        if (($this->get('security.context')->isGranted('ROLE_USER'))) {
            return $this->redirect($this->generateUrl('Myaccount'));
        }
    }

    /**
     * @Route("/list/", name="principal_lista")
     * @Method("GET")
     * @Template()
     */
    public function booksAction() {
        $request = $this->getRequest();
        $page = $request->query->get('page');
        $searchParam = $request->get('searchParam');
        return array(
            'current_page' => $page,
            'searchParam' => $searchParam
        );
    }

    /**
     * consulta a list Books.
     *
     * @Route("/list/ajax/consulta", name="principal_list_ajax")
     * @Method("GET")
     */
    public function ajaxListAction(Request $request) {
        //pagina donde se esta ubicado
        $page = $request->query->get('page');
        //Asignamos el parametro url
        $searchParam = $request->get('searchParam');
        //extraemos variables del array
        extract($searchParam);

        $em = $this->getDoctrine()->getManager();
        $entryQuery = $em->createQueryBuilder()
                ->select('hp', 'p', 'h')
                ->from('AdminAdminBundle:HojadevidaPhoto', 'hp')
                ->leftJoin('hp.idHojadevida', 'h')
                ->leftJoin('hp.idPhoto', 'p')
                ->andWhere('hp.principal =:principal')
                ->addOrderBy('h.Calificacion', 'DESC')
                ->addOrderBy('h.fechaupdate', 'DESC')
                ->setParameter('principal', '1');
        //query aux
        $queryaux = $em->createQueryBuilder()
                ->select('COUNT(hp)')
                ->from('AdminAdminBundle:HojadevidaPhoto', 'hp')
                ->leftJoin('hp.idHojadevida', 'h')
                ->leftJoin('hp.idPhoto', 'p')
                ->andWhere('hp.principal =:principal')
                ->addOrderBy('h.Calificacion', 'DESC')
                ->addOrderBy('h.fechaupdate', 'DESC')
                ->setParameter('principal', '1');
        $total_count = $queryaux->getQuery()->getSingleScalarResult();
        $entryQuery->setFirstResult(($page - 1) * 36)->setMaxResults(36);
        $entryQueryfinal = $entryQuery->getQuery();
        //obtenemos el array de resultados
        $entities = $entryQueryfinal->getArrayResult();
        $pagination = (new Paginator())->setItems($total_count, 36)->setPage($searchParam['page'])->toArray();
        //renderizamos la vista para mostrar las hojas de vida
        return $this->render('AdminAdminBundle:Default:ajax_books_list.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
        ));
    }
    /**
     *
     * @Route("/contact", name="principal_contact")
     * @Method("GET")
     * @Template()
     */
    public function contactAction(){
        return array('xs'=>'cs');
    }
    /**
     *
     * @Route("/contact/send", name="principal_contact_send_mail")
     * @Method("GET")
     */
    public function contactSend_mailAction(request $request){
        ///procedemos a enviar el email
        $asunto = $request->query->get('asunto');
        $Body = $request->query->get('detalle');
        $emailuser = $request->query->get('email');
        $correo_remitente = 'youfikner@gmail.com';
        $Subject = 'Fikner - ' . $emailuser . ':' . $asunto;
        $email = 'luisk__@hotmail.com';
        try{
            $message = \Swift_Message::newInstance()
                ->setSubject($Subject)
                ->setFrom($correo_remitente)
                ->setTo($email)
                ->setBody(
                    <<<EOF
                    Asunto:  $Subject
                Correo:  $emailuser
                
                
                        $Body               
                
                Responda este email a $emailuser
                
EOF
                )
            ;
            $this->get('mailer')->send($message);
        $mail='yes';
        }catch (\Exception $e){
         $mail='Not';
        }
        return $this->render('AdminAdminBundle:views:sendMailMSG.html.twig', array(
            'mail' => $mail
        ));
    }

}
