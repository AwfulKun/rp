<?php

namespace App\Controller;

use App\Entity\AppCharacter;
use App\Entity\AppUser;
use App\Form\AppCharacterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/user")
 */
class AppUserController extends AbstractController
{
    /**
     * @Route("/{id}", name="user_profile", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('app_user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
