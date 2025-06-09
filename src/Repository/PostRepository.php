<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findLatestPostsByCategories(array $categoryIds, int $limitPerCategory = 3): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT p.*, pc.category_id
        FROM post p
        INNER JOIN post_category pc ON p.id = pc.post_id
        WHERE pc.category_id IN (:categoryIds)
        ORDER BY pc.category_id, p.created_at DESC
    ';

        // Use executeQuery on Connection, not on prepared statement
        $stmt = $conn->executeQuery(
            $sql,
            ['categoryIds' => $categoryIds],
            ['categoryIds' => Connection::PARAM_INT_ARRAY]
        );

        $rows = $stmt->fetchAll();

        $postsByCategory = [];

        foreach ($rows as $row) {
            $catId = $row['category_id'];
            if (!isset($postsByCategory[$catId])) {
                $postsByCategory[$catId] = [];
            }
            if (count($postsByCategory[$catId]) < $limitPerCategory) {
                $postsByCategory[$catId][] = $row;
            }
        }

        return $postsByCategory;
    }

}
