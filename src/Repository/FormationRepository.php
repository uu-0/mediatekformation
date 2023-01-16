<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formation>
 *
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    /**
     * @var type String date publication formation
     */
    private $publishedAt = 'f.publishedAt';

    /**
     * Constructeur de classe
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }
    
    /**
     * Méthode d'ajout d'une formation
     * @param Formation $entity
     * @param bool $flush
     * @return void
     */
    public function add(Formation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * Méthode de suppression d'une formation
     * @param Formation $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Formation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Méthode de tri des formations sur un champ, avec table
     * @param type $champ
     * @param type $ordre
     * @param type $table si $champ dans une autre table
     * @return Formation[]
     */
    public function findAllOrderBy($champ, $ordre, $table): array{
        return $this->createQueryBuilder('f')
                    ->join('f.'.$table, 't')
                    ->orderBy('t.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();  
    }
    
    /**
     * Méthode de tri des formations sur un champ, sans table
     * Retourne toutes les formations triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @return array
     */
    public function findByOrderBy($champ, $ordre): array{
            return $this->createQueryBuilder('f')
                    ->orderBy('f.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Méthode de tri d'une playlist sur un champ entré
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table
     * @return Formation[]
     */
     public function findByContainValue($champ, $valeur, $table): array{
        if($valeur==""){
            return $this->findAll();        
        }
        return $this->createQueryBuilder('f')
                ->join('f.'.$table, 't')                    
                ->where('t.'.$champ.' LIKE :valeur')
                ->orderBy($this->publishedAt, 'DESC')
                ->setParameter('valeur', '%'.$valeur.'%')
                ->getQuery()
                ->getResult();                   
    }                       
    
     /**
     * Retourne la valeur renseignée en fonction du champ
     * Ou tous les enregistrements si la valeur est null
     * Avec $champ présent dans une autre entité
     * @param type $champ
     * @param type $valeur
     * @param type $table  la table correspondant au $champ recherché
     * @return Formation[]
     */
    public function findByContainValueTableEmpty($champ, $valeur, $table): array {
        if ($valeur == "") {
            return $this->findAll();
        } else {
            return $this->createQueryBuilder('f')
                            ->where('f.'.$champ.' LIKE :valeur')
                            ->orderBy($this->publishedAt, 'DESC')
                            ->addOrderBy('f.id', 'DESC')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->getQuery()
                            ->getResult();
        }
    }

    /**
     * Retourne les n formations les plus récentes
     * @param type $nb
     * @return Formation[]
     */
    public function findAllLasted($nb) : array {
        return $this->createQueryBuilder('f')
                ->orderBy($this->publishedAt, 'DESC')
                ->setMaxResults($nb)     
                ->getQuery()
                ->getResult();
    }    

    /**
     * Retourne la liste des formations d'une playlist
     * @param type $idPlaylist
     * @return array
     */
    public function findAllForOnePlaylist($idPlaylist): array{
        return $this->createQueryBuilder('f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy($this->publishedAt, 'ASC')   
                ->getQuery()
                ->getResult();        
    }

}