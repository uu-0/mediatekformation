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
class PlaylistRepository extends ServiceEntityRepository {

    /**
     * @var type String playlist id
     */
    private $idPlaylist = 'p.id';
    
    /**
     * @var type String playlist name
     */
    private $namePlaylist = 'p.name';
    
    /**
     * @var type String playlist formation
     */
    private $formations = 'p.formations';
    
    /**
     * @var type String formation catégorie
     */
    private $categories = 'f.categories';

    /**
     * Constructeur de classe
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Playlist::class);
    }

    /**
     * Méthode d'ajout d'une playlist
     * @param Playlist $entity
     * @param bool $flush
     * @return void
     */
    public function add(Playlist $entity, bool $flush = false): void {
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
    public function remove(Playlist $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Méthode de tri sur le nom d'une playlist
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByName($ordre): array {
        return $this->createQueryBuilder('p')
                        ->leftjoin($this->formations, 'f')
                        ->groupBy($this->idPlaylist)
                        ->orderBy($this->namePlaylist, $ordre)
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Méthode de tri sur le nombre de formation d'une playlist
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByNbFormation($ordre): array {
        return $this->createQueryBuilder('p')
                        ->leftjoin($this->formations, 'f')
                        ->groupBy($this->idPlaylist)
                        ->orderBy('count(f.title)', $ordre)
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Méthode de tri d'une playlist sur un champ entré
     * @param type $champ
     * @param type $valeur
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur): array {
        if ($valeur == "") {
            return $this->findAll();
        } else {
            return $this->createQueryBuilder('p')
                            ->leftjoin($this->formations, 'f')
                            ->where('p.' . $champ . ' LIKE :valeur')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->groupBy($this->idPlaylist)
                            ->orderBy($this->namePlaylist, 'ASC')
                            ->getQuery()
                            ->getResult();
        }
    }

    /**
     * Retourne la valeur renseignée en fonction du champ
     * Ou tous les enregistrements si la valeur est null
     * Avec $champ présent dans une autre entité
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return array
     */
    public function findByContainValueTable($champ, $valeur, $table): array {
        if ($valeur == "") {
            return $this->findAll();
        } else {
            return $this->createQueryBuilder('p')
                            ->leftjoin($this->formations, 'f')
                            ->leftjoin($this->categories, 'c')
                            ->where('c.' . $champ . ' LIKE :valeur')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->groupBy($this->idPlaylist)
                            ->orderBy($this->namePlaylist, 'ASC')
                            ->getQuery()
                            ->getResult();
        }
    }

}