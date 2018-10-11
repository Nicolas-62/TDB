<?php

namespace TDB\PasswordManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use TDB\PasswordManagerBundle\Form\RechercheType;
use TDB\PasswordManagerBundle\Form\EntreeType;
use TDB\PasswordManagerBundle\Form\ServiceType;
use TDB\PasswordManagerBundle\Form\AccesType;
use TDB\PasswordManagerBundle\Form\NotesType;
use TDB\PasswordManagerBundle\Entity\Entree;
use TDB\PasswordManagerBundle\Entity\Service;
use TDB\PasswordManagerBundle\Entity\Acces;
use Symfony\Component\HttpFoundation\JsonResponse;


class TdbController extends Controller
{
    /* indexAction
     * @description : Fournit la vue de l'application et l'ensemble des entrées contenu dans la BDD.
     * @param $request : Objet qui contient entre autres une requête vide qui attends toutes les entrées en BDD en retour (AJAX).
     * @return HTML/json
     */
    public function indexAction(Request $request)
    {
        // Recupération du repository qui contient nos méthodes personnelle de requête en BDD pour l'entité Entree.
        $repository = $this->getDoctrine()
        ->getManager()
        ->getRepository('TDBPasswordManagerBundle:Entree');
        // Création des formulaires de l'application
        $form_recherche = $this->createForm(RechercheType::class);
        $form_entree    = $this->createForm(EntreeType::class);
        $form_service   = $this->createForm(ServiceType::class);
        $form_acces     = $this->createForm(AccesType::class);
        $form_notes     = $this->createForm(NotesType::class);

        // Si une requête a été envoyée, requète vide envoyé par javascript une fois la page chargée afin de récupérer toutes les entrées et leur détail par javascript.
        if ($request->isMethod('POST')) {
            // Recuperation des entrées par méthode personnelle (tri asc)
            $entrees    = $repository->entreeAll();
        }   
        // Si c'est une requête AJAX
        if($request->isXmlHttpRequest()){
            // On transforme les objets doctrine en tableaux imbriqués. 
            $formatage = $this->container->get('tdb_password_manager.outils');
            $entrees   = $formatage->formatEntrees($entrees);
            // Les tableaux envoyés, lors du passage au format json vont être transformés en objets et reçu comme tel par Javascript.
            return new JsonResponse($entrees);           
        } 
        // On charge la vue enfant la plus petite et les formulaires.      
        return $this->render('TDBPasswordManagerBundle:Entree:detail.html.twig', array(
            'form_recherche' => $form_recherche->CreateView(),
            'form_entree'    => $form_entree->CreateView(),
            'form_service'   => $form_service->CreateView(),
            'form_acces'     => $form_acces->CreateView(),
            'form_notes'     => $form_notes->CreateView(),
        ));
    }
    /* addEntreeAction
     * @description : Créer une nouvelle entrée.
     * @param $request : donnée du formulaire d'ajout d'entrée (nom).
     * @return HTML/json
     */
    public function addEntreeAction(Request $request)
    {
        // Création d'un formulaire avec l'objet entrée et ses attributs comme entrées du formulaire.
        $entree      = new Entree();
        $form_entree = $this->createForm(EntreeType::class, $entree);
        // On récupère les variables de la requète postée et on hydrate l'objet entrée via le formulaire.
        $form_entree->handleRequest($request);
        // Si le formualaire a bien été soumis et est valide.
        if ($form_entree->isSubmitted() && $form_entree->isValid()) {
            // On vérifie qu'il n'éxiste pas déjà une entrée du même nom
            $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('TDBPasswordManagerBundle:Entree');
            $entreeEnBase = $repository->findByEntreeNom($entree->getEntreeNom());

            if($entreeEnBase == false){
                // On sauvegarde l'entrée.
                $em = $this->getDoctrine()->getManager();
                $em->persist($entree);
                $em->flush();
            }else{
                $entree = false;
            }
        } else{
            $entree = false;
        }
        // Si c'est une requête AJAX.
        if($request->isXmlHttpRequest()){
            if($entree != false){
                // On formate en envoie les données en json.
                $formatage = $this->container->get('tdb_password_manager.outils');
                $entree    = $formatage->formatEntree($entree);
            }
            return new JsonResponse($entree);
        }else{
            $session = $request->getSession();
            if($entree == false){
                $session->getFlashBag()->add('info', "L'entrée n'a pu être enregistrée." );
            }else{
                $session->getFlashBag()->add('info', 'Entrée enregistrée');
            }       
            $session->getFlashBag()->add('info', 'Javascript semble désactivé, ce site a besoin de Javascript pour fonctionner.');
        }
        // Sinon on retourne à l'accueil.
        return $this->redirectToRoute('tdb_password_manager_home');          
    }
    /* editNotesAction
     * @description : Sauvegarde les notes d'une entrée.
     * @param $request : Objet qui contient les  données du formulaire d'édition des notes.
     * @param $entree_id : id de l'entrée dont fait partie les notes
     * @return HTML/json
     */
    public function editNotesAction(Request $request, $entree_id){

        // Les notes sont un attribut de l'entitée Entree. On récupère l'entrée correspondante.
        $em     = $this->getDoctrine()->getManager();
        $entree = $em->getRepository('TDBPasswordManagerBundle:Entree')->find($entree_id);

        if ($entree != null) {
            // On va hydrater l'objet entrée dont fait partie les notes.
            $form_notes = $this->createForm(NotesType::class, $entree);
            $form_notes->handleRequest($request);
            if ($form_notes->isSubmitted() && $form_notes->isValid()){
                // On sauvegarde l'entrée
                $em->flush();
            }
        }
        if($request->isXmlHttpRequest()){
            $formatage  = $this->container->get('tdb_password_manager.outils');
            $entree    = $formatage->formatEntreeNotes($entree);
            return new JsonResponse($entree);
        }
        return $this->redirectToRoute('tdb_password_manager_home');                     
    }
    /* deleteEntreeAction
     * @description : Suppression d'une entrée.
     * @param $request : Objet qui contient les infos concernant la requête.
     * @param $entree_id : id de l'entrée à supprimer.
     * @return HTML/json
     */
    public function deleteEntreeAction(Request $request, $entree_id)
    {
        $em     = $this->getDoctrine()->getManager();
        $entree = $em->getRepository('TDBPasswordManagerBundle:Entree')->find($entree_id);
        if ($entree != null) {
            // Suppression des services et accès associés à l'entrée (suppression manuelle car présence d'une clé étrangère entree_id et service_id)
            if ($request->isMethod('POST')) {
                foreach($entree->getServices() as $service){
                    foreach($service->getAccess() as $acces){
                        $em->remove($acces);
                    }
                    $em->remove($service);
                }
                $em->remove($entree);
                $em->flush();
            }
            $entree = true;
        } 
        // Si c'est une requète AJAX
        if($request->isXmlHttpRequest()){

            return new JsonResponse($entree);
        }   
        return $this->redirectToRoute('tdb_password_manager_home'); 
    }   
    /* addServiceAction
     * @description : Ajout d'un service
     * @param $request : Objet qui contient les infos concernant la requête.
     * @param $entree_id : id de l'entrée associée.
     * @return HTML/json
     */
    public function addServiceAction(Request $request, $entree_id)
    {   
        // On récupère l'entrée associée au service qu'on veut créer.
        $em = $this->getDoctrine()->getManager();
        $entree = $em->getRepository('TDBPasswordManagerBundle:Entree')->findEntreeById($entree_id);

        if ($entree != null) {
            $service = new Service();
            $form_service = $this->createForm(ServiceType::class, $service);
            $form_service->handleRequest($request);

            if ($form_service->isSubmitted() && $form_service->isValid()) { 
                // On vérifie qu'il n'y a pas déjà un service du même nom.
                $nom = strtolower($service->getServiceNom());
                $flag = false;
                foreach($entree->getServices() as $serviceDb){
                    if(strtolower($serviceDb->getServiceNom()) == $nom){
                        $flag = true;
                    }
                }
                if($flag == true){
                    $service = "already";
                }else{
                // Si le service n'existe pas déjà on le lie à l'entrée et l'enregistre.           
                    $entree->addService($service);
                    // Création des acces 'login' et 'password' au service.
                    $accesLogin = new Acces();
                    $accesLogin->setClef('Login');
                    $accesLogin->setValeur('');
                    $service->addAcces($accesLogin);

                    $accesPassword = new Acces();
                    $accesPassword->setClef('Password');
                    $accesPassword->setValeur('');
                    $service->addAcces($accesPassword);

                    $em->persist($accesLogin);
                    $em->persist($accesPassword);
                    $em->persist($service);
                    $em->flush();
                }
            }  
        }else{
            $service = false;
        }
        if($request->isXmlHttpRequest()){
            if($service != false && $service != "already"){
                $formatage  = $this->container->get('tdb_password_manager.outils');
                $service    = $formatage->formatService($service);
            }
            return new JsonResponse($service);
        }   
        return $this->redirectToRoute('tdb_password_manager_home');        
    }
    /* deleteServiceAction
     * @description : Suppression d'un service.
     * @param $request : Objet qui contient les infos concernant la requête.
     * @param $service_id : id du service.
     * @return HTML/json
     */
    public function deleteServiceAction(Request $request, $service_id)
    {
        $em     = $this->getDoctrine()->getManager();
        $service = $em->getRepository('TDBPasswordManagerBundle:Service')->find($service_id);

        if ($service != null) {
            // Suppression des accès associés au service (suppression manuelle car présence d'une clé étrangère service_id)
            if ($request->isMethod('POST')) {

                foreach($service->getAccess() as $acces){
                    $em->remove($acces);
                }
                $em->remove($service);
                $em->flush(); 
            }
            $service = true;
        }
        if($request->isXmlHttpRequest()){

            return new JsonResponse($service);
        }   
        return $this->redirectToRoute('tdb_password_manager_home'); 
    }
    /* addAccesAction
     * @description : Ajout d'un acces.
     * @param $request : Objet qui contient les infos concernant la requête.
     * @param $service_id : id du service associé.
     * @return HTML/json
     */
    public function addAccesAction(Request $request, $service_id)
    {   
        $em = $this->getDoctrine()->getManager();
        $service = $em->getRepository('TDBPasswordManagerBundle:Service')->findServiceById($service_id);
     
        if ($service != null) {
            $acces = new Acces();
            $form_acces = $this->createForm(AccesType::class, $acces);
            $form_acces->handleRequest($request);
            if ($form_acces->isSubmitted() && $form_acces->isValid()) { 
                // On vérifie qu'il n'exista pas déjà un accès du même nom.
                $nom = strtolower($acces->getClef());
                $flag = false;
                foreach($service->getAccess() as $accesBd){
                    if(strtolower($accesBd->getClef()) == $nom){
                        $flag = true;
                    }
                } 
                if($flag == true){
                    $acces = 'already';
                }else{ 
                // On lie l'accès au service et l'enregistre en BD.          
                    $service->addAcces($acces);
                    $em->persist($acces);
                    $em->persist($service);
                    $em->flush();
                }
            } 
        }else{
            $acces = false;
        }
        if($request->isXmlHttpRequest()){
            if($acces != false && $acces != 'already'){
                $formatage  = $this->container->get('tdb_password_manager.outils');
                $acces    = $formatage->formatAcces($acces);
            }
            return new JsonResponse($acces);
        }   
        return $this->redirectToRoute('tdb_password_manager_home');        
    }
    /* editAccesAction
     * @description : Modification d'un acces.
     * @param $request : Objet qui contient les infos concernant la requête.
     * @param $acces_id : id de l'acces.
     * @return HTML/json
     */
    public function editAccesAction(Request $request, $acces_id)
    {   
        $em = $this->getDoctrine()->getManager();
        $acces = $em->getRepository('TDBPasswordManagerBundle:Acces')->find($acces_id);
     
        if ($acces != null) {
            $form_acces = $this->createForm(AccesType::class, $acces);
            $form_acces->handleRequest($request);
            if ($form_acces->isSubmitted() && $form_acces->isValid()) {              
                $em->flush();
            }  
        }
        if($request->isXmlHttpRequest()){
            if($acces != null){
                $formatage  = $this->container->get('tdb_password_manager.outils');
                $acces    = $formatage->formatAcces($acces);
            }
            return new JsonResponse($acces);
        }   
        return $this->redirectToRoute('tdb_password_manager_home');        
    }
    /* deleteAccesAction
     * @description : Suppression d'un acces.
     * @param $request : Objet qui contient les infos concernant la requête.
     * @param $acces_id : id de l'acces.
     * @return HTML/json
     */
    public function deleteAccesAction(Request $request, $acces_id)
    {
        $em     = $this->getDoctrine()->getManager();
        $acces = $em->getRepository('TDBPasswordManagerBundle:Acces')->find($acces_id);
        if($acces != null){
            if ($request->isMethod('POST')) {
                $em->remove($acces);
                $em->flush();
                $acces = true; 
            }
        }
        if($request->isXmlHttpRequest()){
            return new JsonResponse($acces);
        }   
        return $this->redirectToRoute('tdb_password_manager_home'); 
    }
}