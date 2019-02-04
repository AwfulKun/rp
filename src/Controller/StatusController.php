<?php

namespace App\Controller;

use App\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends AbstractController
{
    /**
     * @Route("/status", name="status")
     */
    public function index()
    {
        $status = $this->getDoctrine()
            ->getRepository(Status::class)
            ->findAll();

        return $this->json($status);
    }
}
