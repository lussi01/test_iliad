<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OrdineRepository;
use Psr\Log\LoggerInterface;

class OrderController extends AbstractController
{
    private $ordineRepository;
    private $logger;

    public function __construct(OrdineRepository $ordineRepository, LoggerInterface $logger)
    {
        $this->ordineRepository = $ordineRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/order", name="orders_post", methods={"POST"})
     */
    public function createOrder(Request $request): Response
    {
        $this->logger->info('Received request to create order');

        $data = json_decode($request->getContent(), true);
        $this->logger->info('Request data decoded', $data);

        if (
            !$data ||
            !isset($data['Nome'], $data['Cognome'], $data['Product_ID']) ||
            empty(trim($data['Nome'])) ||
            empty(trim($data['Cognome'])) ||
            !is_numeric($data['Product_ID'])
        ) {
            $this->logger->error('Dati non validi forniti', ['data' => $data]);
            return new Response('Dati non validi', Response::HTTP_BAD_REQUEST);
        }

        try {
            $created = $this->ordineRepository->createOrder(
                trim($data['Nome']),
                trim($data['Cognome']),
                (int) $data['Product_ID']
            );
            dump($data);

            if ($created) {
                return new Response('Ordine creato con successo!', Response::HTTP_CREATED);
            } else {
                return new Response('Errore durante la creazione dell\'ordine', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            $this->logger->error('Errore durante la creazione dell\'ordine: ' . $e->getMessage());
            return new Response('Errore interno del server', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/order", name="orders_get", methods={"GET"})
     */
    public function getOrders(): Response
    {
        $orders = $this->ordineRepository->getAllOrdersWithProducts();

        if (empty($orders)) {
            return new Response('No orders found', Response::HTTP_NOT_FOUND);
        }

        return new Response(
            json_encode($orders),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * @Route("/order/{id}", name="orders_delete", methods={"DELETE"})
     */
    public function deleteOrder($id): Response
    {
        try {
            $this->logger->info('Received delete request for OrderID: ' . $id);
            $this->ordineRepository->deleteOrderById($id);
            $this->logger->info('Order deleted successfully for OrderID: ' . $id);
            return new Response('Order deleted successfully!', Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logger->error('Error deleting order: ' . $e->getMessage());
            return new Response('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/order/{id}", name="orders_update", methods={"PUT"})
     */
    public function updateOrder($id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['Nome'], $data['Cognome'], $data['Product_ID'])) {
            $this->logger->error('Invalid data provided');
            return new Response('Invalid data', Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->ordineRepository->updateOrder(
                $id,
                $data['Nome'],
                $data['Cognome'],
                $data['Product_ID'],
            );
            echo "FLAG PRINT", $data;
            return new Response('Order updated successfully!', Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logger->error('Error updating order: ' . $e->getMessage());
            return new Response('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
