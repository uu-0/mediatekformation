<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 *
 * @method Playlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playlist[]    findAll()
 * @method Playlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistRepository extends ServiceEntityRepository
{
    
    private $id = 'p.id id';
    private $name = 'p.name name';
    private $categoryName = 'c.name categoriename';
    private $formations = 'p.formations';
    private $categories = 'f.categories';
    private $categoriesName = 'c.name';

    /**
     * Constructeur
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    /**
     * Méthode d'ajout d'une playlist 
     * @param Playlist $entity
     * @param bool $flush
     * @return void
     */
    public function add(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * Méthode de suppression d'une playlist
     * @param Playlist $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
    * Retourne toutes les playlists triées sur le nom de la playlist
    * @param type $champ
    * @param type $ordre
    * @return Playlist[]
    */
    public function findAllOrderByName($ordre): array{
        return $this->createQueryBuilder('p')
            ->leftjoin($this->formations, 'f')
            ->groupBy('p.id')
            ->orderBy('p.name', $ordre)
            ->getQuery()
            ->getResult();
    }
        
    /**
    * Retourne toutes les playlists triées sur le nombre de formations
    * @param type $ordre
    * @return Playlist[]
    */
    public function findAllOrderByNbFormations($ordre): array{
        return $this->createQueryBuilder('p')
           ->leftjoin($this->formations, 'f')
           ->groupBy('p.id')
           ->orderBy('count(f.title)', $ordre)
           ->getQuery()
           ->getResult();     
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Playlist[]
     */
   public function findByContainValueEmpty($champ, $valeur, $table=""): array{
        if($valeur==""){
            return $this->findAllOrderByName('ASC');
        }    
         return $this->createQueryBuilder('p')
                ->select($this->id)
                ->addSelect($this->name)
                ->addSelect($this->categoryName)
                ->leftjoin($this->formations, 'f')
                ->leftjoin($this->categories, 'c')
                ->where('p.'.$champ.' LIKE :valeur')
                ->setParameter('valeur', '%'.$valeur.'%')
                ->groupBy('p.id')
                ->addGroupBy($this->categoriesName)
                ->orderBy('p.name', 'ASC')
                ->addOrderBy($this->categoriesName)
                ->getQuery()
                ->getResult();    
              
}    
    
    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur, $table=""): array {
        if($valeur==""){
            return $this->findAllOrderByName('ASC');
        }  
        return $this->createQueryBuilder('p')
                ->select($this->id)
                ->addSelect($this->name)
                ->addSelect($this->categoryName)
                ->leftjoin($this->formations, 'f')
                ->leftjoin($this->categories, 'c')
                ->where('c.'.$champ.' LIKE :valeur')
                ->setParameter('valeur', '%'.$valeur.'%')
                ->groupBy('p.id')
                ->addGroupBy($this->categoriesName)
                ->orderBy('p.name', 'ASC')
                ->addOrderBy($this->categoriesName)
                ->getQuery()
                ->getResult();              
    } 
    



}