<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    // src/Repository/MessageRepository.php

    public function findUserChats($userId)
    {
        return $this->createQueryBuilder('m')
        ->select('m')
        ->where('m.sender = :userId OR m.receiver = :userId')
        ->setParameter('userId', $userId)
        ->orderBy('m.sent_at', 'DESC')
        ->getQuery()
        ->getResult();
    }

    public function findUserMessages($userId, $otherUser)
    {
        return $this->createQueryBuilder('m')
        ->select('m')
        ->where('(m.sender = :userId OR m.receiver = :userId) AND (m.sender = :otherUser OR m.receiver = :otherUser)')
        ->setParameter('userId', $userId)
        ->setParameter('otherUser', $otherUser)
        ->orderBy('m.sent_at', 'ASC')
        ->getQuery()
        ->getResult();
    }

    //    /**
    //     * @return Message[] Returns an array of Message objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Message
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}