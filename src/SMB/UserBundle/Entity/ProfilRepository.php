<?php

namespace SMB\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProfilRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProfilRepository extends EntityRepository
{
	public function supprimer_profil($id){

		$query=$this->_em->createQuery('DELETE FROM SMB\UserBundle\Entity\Profil p WHERE p.id='.$id);
		$result=$query->getResult();
	}	
}
