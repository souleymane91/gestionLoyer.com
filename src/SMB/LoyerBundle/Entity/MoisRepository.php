<?php

namespace SMB\LoyerBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MoisRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MoisRepository extends EntityRepository
{
    /**************************************************************
    * méthode pour recupérer la liste des mois payé par l'étudiant 
    * passé en argument de la méthode
    ***************************************************************/
    public function mois_paye($paiement){

            $qb=$this->createQuerybuilder('m');
            $qb->join('m.paiements','pa')
                    ->addSelect('pa')
                    ->where('pa.id =:id')
                    ->setParameter('id',$paiement->getId());

            return $qb->getQuery()->getResult();
    }
        
        
    /************************************************
    * fonction qui permet de supprimer un mois
    * Renvoie la liste des mois non supprimés
    *************************************************/
    public function supprimer_mois($id){
        $qb = $this->createQueryBuilder('m')
                   ->update()
                   ->set('m.supprime', TRUE)
                   ->where('m.id = :id')
                   ->setParameter('id',$id);
        return $qb->getQuery()->getResult();
    }
    
    /***************************************************************
    * fonction qui vérifie si un mois est supprimé ou pas
    * Renvoie true si le mois est supprimé et false sinon
    **************************************************************/
    public function estSupprime($id){
        $qb = $this->createQueryBuilder('m')
                   ->where('m.id = :id')
                   ->setParameter('id',$id)
                   ->andWhere('m.supprime = :bol')
                   ->setParameter('bol', TRUE)
                   ->getQuery();
        $result = $qb->getOneOrNullResult();
        if(!$result){
            return FALSE;
        }
        else{
            return TRUE;
        }
    }
    /*****************************************************************
    * Recupérer l'ensemble des mois qui ne sont pas supprimés
    *****************************************************************/
    public function listMois(){
       //On recupère l'ensemble des mois
       $qb=$this->createQuerybuilder('m')
                ->where('m.supprime = :bol')
                ->setParameter('bol', FALSE);
       return $qb->getQuery()->getResult();
    }
}
