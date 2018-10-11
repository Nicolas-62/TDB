<?php



namespace TDB\PasswordManagerBundle\Outils;

use \stdClass;

class TDBOutils
{

	public function formatEntrees($doctrineEntrees){

        $array_entrees  =       array();
        foreach($doctrineEntrees as $doctrineEntree){  
            $entree = new stdClass;   
            // récupération des attributs  
            $entree->id = $doctrineEntree->getId();
            $entree->entree_nom = $doctrineEntree->getEntreeNom();
            $entree->notes = $doctrineEntree->getNotes();

            // Parcours les services.
            $array_services =   array();
            foreach($doctrineEntree->getServices() as $doctrineService){
                $service = new stdClass;
                $service->id = $doctrineService->getId();
                $service->service_nom = $doctrineService->getServiceNom();
                $service->entree_id = $doctrineService->getEntreeId();   

                // Parcours les acces.
                $array_acces    =   array();
                foreach($doctrineService->getAccess() as $doctrineAcces){
                    $acces = new stdClass;
                    $acces->id = $doctrineAcces->getId();
                    $acces->clef = $doctrineAcces->getClef();
                    $acces->valeur = $doctrineAcces->getValeur();
                    $acces->service_id = $doctrineAcces->getServiceId();
                    // On récupère les acces indexés.
                    $array_acces[$acces->id]    =   $acces;
                }               
                // On affecte les acces indexés au service.
                $service->access = $array_acces;
                
                // On récupère les services indexés.
                $array_services[$service->id]   =   $service;               
            }           
            // On affecte les services indexés à l'entrée.
            $entree->services=$array_services;
            
            // On récupère les services indexés.
            $array_entrees[$entree->id] = $entree;          
        } 
        return $array_entrees;
	}
    public function formatEntree($doctrineEntree){

        $entree = new stdClass;   
        // récupération des attributs  
        $entree->id = $doctrineEntree->getId();
        $entree->entree_nom = $doctrineEntree->getEntreeNom();
        $entree->notes = $doctrineEntree->getNotes();

        // Parcours les services.
        $array_services =   array();
        foreach($doctrineEntree->getServices() as $doctrineService){
            $service = new stdClass;
            $service->id = $doctrineService->getId();
            $service->service_nom = $doctrineService->getServiceNom();
            $service->entree_id = $doctrineService->getEntreeId();   

            // Parcours les acces.
            $array_acces    =   array();
            foreach($doctrineService->getAccess() as $doctrineAcces){
                $acces = new stdClass;
                $acces->id = $doctrineAcces->getId();
                $acces->clef = $doctrineAcces->getClef();
                $acces->valeur = $doctrineAcces->getValeur();
                $acces->service_id = $doctrineAcces->getServiceId();
                // On récupère les acces indexés.
                $array_acces[$acces->id]    =   $acces;
            }               
            // On affecte les acces indexés au service.
            $service->access = $array_acces;
            
            // On récupère les services indexés.
            $array_services[$service->id]   =   $service;               
        }           
        // On affecte les services indexés à l'entrée.
        $entree->services=$array_services;
        return $entree;
    }

    public function formatService($doctrineService){

        $service = new stdClass;
        $service->id = $doctrineService->getId();
        $service->service_nom = $doctrineService->getServiceNom();
        $service->entree_id = $doctrineService->getEntreeId();   

        // Parcours les acces.
        $array_acces    =   array();
        foreach($doctrineService->getAccess() as $doctrineAcces){
            $acces = new stdClass;
            $acces->id = $doctrineAcces->getId();
            $acces->clef = $doctrineAcces->getClef();
            $acces->valeur = $doctrineAcces->getValeur();
            $acces->service_id = $doctrineAcces->getServiceId();
            // On récupère les acces indexés.
            $array_acces[$acces->id]    =   $acces;
        }               
        // On affecte les acces indexés au service.
        $service->access = $array_acces;                   

        return $service;
    }    
    public function formatAcces($doctrineAcces){

        $acces = new stdClass;
        $acces->id = $doctrineAcces->getId();
        $acces->clef = $doctrineAcces->getClef();
        $acces->valeur = $doctrineAcces->getValeur();
        $acces->service_id = $doctrineAcces->getServiceId();       

        return $acces;
    } 
    public function formatEntreeNotes($doctrineEntree){

        $entree = new stdClass;   
        // récupération des attributs  
        $entree->id = $doctrineEntree->getId();
        $entree->notes = $doctrineEntree->getNotes();

        return $entree;
    } 
}