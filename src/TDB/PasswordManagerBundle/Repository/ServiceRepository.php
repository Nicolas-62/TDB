<?php

namespace TDB\PasswordManagerBundle\Repository;

/**
 * ServicesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ServiceRepository extends \Doctrine\ORM\EntityRepository
{
	// Fonction qui cherche un service par son id et retourne ses accès associés.
	public function findServiceById($id){

		$qb = $this->createQueryBuilder('service');

		$qb
			->leftJoin('service.access', 'acc')
			->addSelect('acc')
			->where('service.id = :id')
			->setParameter('id', $id)
		;
		return $qb
			->getQuery()
			->getSingleResult()
		;			
	}
}
