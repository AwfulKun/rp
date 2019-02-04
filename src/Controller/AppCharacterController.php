<?php

namespace App\Controller;

use App\Entity\AppCharacter;
use App\Form\AppCharacterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/character")
 */
class AppCharacterController extends AbstractController
{
    /**
     * @Route("/", name="characters_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser()->getId();

        $appCharacters = $this->getDoctrine()
            ->getRepository(AppCharacter::class)
            ->createQueryBuilder('a')
            ->select('a', 'appUser')
            ->join('a.appUser', 'appUser')
            ->where('appUser.id = :id')
            ->setParameter('id', $user)
            ->getQuery()
            ->getResult();

        return $this->render('app_character/index.html.twig', [
            'app_characters' => $appCharacters,
        ]);
    }

    /**
     * @Route("/api", name="characters_index", methods={"GET"})
     */
    public function index_api(): Response
    {
        $user = $this->getUser()->getId();

        $characters = $this->getDoctrine()
            ->getRepository(AppCharacter::class)
            ->createQueryBuilder('a')
            ->select('a')
            ->where('a.appUser = :id')
            ->setParameter('id', $user)
            ->getQuery()
            ->getResult();

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object, string $format = null, array $context = []) {
            return $object->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonResponse = new JsonResponse();
        return $jsonResponse->setContent($serializer->serialize($characters, 'json'));
    }

    /**
     * @Route("/new", name="app_character_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $appCharacter = new AppCharacter();
        $form = $this->createForm(AppCharacterType::class, $appCharacter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $appCharacter->setAppUser($this->getUser());
            $entityManager->persist($appCharacter);
            $entityManager->flush();

            return $this->redirectToRoute('characters_index');
        }

        return $this->render('app_character/new.html.twig', [
            'app_character' => $appCharacter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_character_show", methods={"GET"})
     */
    public function show(AppCharacter $appCharacter): Response
    {
        return $this->render('app_character/show.html.twig', [
            'app_character' => $appCharacter,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_character_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AppCharacter $appCharacter): Response
    {
        $form = $this->createForm(AppCharacterType::class, $appCharacter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('characters_index', [
                'id' => $appCharacter->getId(),
            ]);
        }

        return $this->render('app_character/edit.html.twig', [
            'app_character' => $appCharacter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_character_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AppCharacter $appCharacter): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appCharacter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($appCharacter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('characters_index');
    }
}
