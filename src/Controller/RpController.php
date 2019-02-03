<?php

namespace App\Controller;

use App\Entity\Rp;
use App\Form\RpType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/rp")
 */
class RpController extends BaseController
{
    /**
     * @Route("/", name="rp_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser()->getId();

        // $rps = $this->getDoctrine()
        //     ->getRepository(Rp::class)
        //     ->findAll();

        $rps = $this->getDoctrine()
            ->getRepository(Rp::class)
            ->createQueryBuilder('r')
            ->select('r', 'a')
            ->join('r.appUser', 'a')
            ->where('a.id = :id')
            ->setParameter('id', $user)
            ->getQuery()
            ->getResult();


        return $this->render('rp/index.html.twig', [
            'rps' => $rps,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/api", name="rp_index_api", methods="GET")
     */
    public function indexApi(): Response
    {
        $user = $this->getUser()->getId();

        $rps = $this->getDoctrine()
            ->getRepository(Rp::class)
            ->createQueryBuilder('r')
            ->select('r')
            ->join('r.appUser', 'a')
            ->leftJoin('r.appCharacter', 'appCharacter')
            ->where('a.id = :id')
            // ->groupBy('r.id')
            ->setParameter('id', $user)
            ->getQuery()
            ->getResult();
        
        

        // $user = $this->getUser();     // get the current user.
        // $rps = $this->getDoctrine()->getRepository(Rp::class)->findBy(array('appUser' => $user->getId()));     // Find all lists owned by current user.
        
        // $normalizer = new ObjectNormalizer();  
        // $normalizer->setIgnoredAttributes(array('appUser'));    // I only want data about each list. $userid is a reference back to the user that owns the list. So we ignore userid.
        // $encoder = new JsonEncoder();
        // $normalizer = new ObjectNormalizer();

        // // all callback parameters are optional (you can omit the ones you don't use)
        // $normalizer->setCircularReferenceHandler(function ($object, string $format = null, array $context = []) {
        //     return $object->getTitle();
        // });

        // $serializer = new Serializer([$normalizer], [$encoder]);

        // return $this->json($this->serialize($rps));

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        // $normalizer->setIgnoredAttributes(array("appUser", "appCharacter"));
        $normalizer->setCircularReferenceHandler(function ($object, string $format = null, array $context = []) {
            return $object->getTitle();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonResponse = new JsonResponse();
        return $jsonResponse->setContent($serializer->serialize($rps, 'json'));
        // return $this->json($serializer->serialize($rps, 'json'));

    }

    /**
     * @Route("/new", name="rp_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rp = new Rp();
        $form = $this->createForm(RpType::class, $rp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $rp->setAppUser($this->getUser());
            $entityManager->persist($rp);
            $entityManager->flush();

            return $this->redirectToRoute('rp_index');
        }

        return $this->render('rp/new.html.twig', [
            'rp' => $rp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rp_show", methods={"GET"})
     */
    public function show(Rp $rp): Response
    {
        return $this->render('rp/show.html.twig', [
            'rp' => $rp,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rp_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rp $rp): Response
    {
        $form = $this->createForm(RpType::class, $rp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rp_index', [
                'id' => $rp->getId(),
            ]);
        }

        return $this->render('rp/edit.html.twig', [
            'rp' => $rp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rp_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Rp $rp): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rp->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rp_index');
    }
}
