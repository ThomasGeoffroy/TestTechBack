<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use App\Services\StripeService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{

    protected $stripeService;

    public function __construct(ManagerRegistry $registry, StripeService $stripeService)
    {
        parent::__construct($registry, Article::class);
        $this->stripeService = $stripeService;
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function articlesMainBackOffice(){

        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT * FROM article
            LIMIT 5
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

         return $resultSet->fetchAllAssociative();
    }


    public function intentSecret(Article $article){
        $intent = $this->stripeService()->paymentIntent($article);

        return $intent['client_secret'] ?? null;
    }

    public function stripe(array $stripeParameter, Article $article)
    {
        $data = $this->stripeService()->stripe($stripeParameter, $article);

        if($data) {
            $ressource = [
                'stripeBrand' => $data['charges']['data'][0]['payment_method_details']['card']['brand'],
                'stripeLast4' => $data['charges']['data'][0]['payment_method_details']['card']['last4'],
                'stripeId' => $data['charges']['data'][0]['id'],
                'stripeStatus' => $data['charges']['data'][0]['id']['status'],
                'stripeToken' => $data['client_secret'],
            
            ];
        }
        return $ressource;
    }

    public function create_subscription(array $ressource, Article $article, User $user)
    {
        $order = new Order();
        $order->setUser($user);
        $order->setArticle($article);
        $order->setPrice($article->getPrice());

    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
