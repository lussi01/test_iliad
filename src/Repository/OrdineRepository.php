<?php

namespace App\Repository;

use App\Entity\Ordine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class OrdineRepository extends ServiceEntityRepository
{
    private $entityManager;
    private $logger;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        parent::__construct($registry, Ordine::class);
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function createOrdine($nome, $cognome, $productId, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        // Verifica se i dati sono stati forniti correttamente
        if (!isset($data['nome'], $data['cognome'], $data['productId'])) {
            return new Response('Dati non validi', Response::HTTP_BAD_REQUEST);
        }

        // Creazione di una nuova istanza di Ordine
        $ordine = new Ordine();
        $ordine->setNome($data['nome']);
        $ordine->setCognome($data['cognome']);
        $ordine->setData(new \DateTime()); // Data corrente
        $ordine->setProductId($data['productId']);

        // Persistenza dell'entità utilizzando l'EntityManager
        try {
            $entityManager->persist($ordine);
            $entityManager->flush();

            return new Response('Ordine creato con successo', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new Response('Errore durante la creazione dell\'ordine: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllOrdersWithProducts()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql =  ' SELECT o.Order_ID, o.Nome AS OrdineNome, o.Cognome, o.Data AS OrdineData, p.Name AS ProdottoNome, p.Product_ID AS ProductID
        FROM Ordine o
        INNER JOIN Prodotto p 
        ON o.Product_ID = p.Product_ID
        ORDER BY o.Data DESC
        ';
        $stmt = $conn->executeQuery($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();

    }

    public function deleteOrderById($orderId)
    {
        $entityManager = $this->getEntityManager();
        $order = $entityManager->getRepository(Ordine::class)->find($orderId);

        if (!$order) {
            throw new \Exception('Order not found');
        }

        try {
            $entityManager->remove($order);
            $entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete order: ' . $e->getMessage());
        }
    }

    public function updateOrder($orderId, $nome, $cognome, $productId)
    {
        $entityManager = $this->getEntityManager();
        $order = $this->entityManager->getRepository(Ordine::class)->find($orderId);

        if (!$order) {
            throw new \Exception('Order not found');
        }

        try {
            $order->setNome($nome);
            $order->setCognome($cognome);
            $order->setData(new \DateTime());
            $order->setProductId($productId);

            $this->entityManager->flush();

            return true; // Return true or handle success as needed
        } catch (\Exception $e) {
            throw new \Exception('Failed to update order: ' . $e->getMessage());
        }
    }
}
