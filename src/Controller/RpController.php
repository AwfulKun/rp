<?php

namespace App\Controller;

use App\Entity\Rp;
use App\Entity\Status;
use App\Entity\AppCharacter;
use App\Entity\AppUser;
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
     * @Route("/index/{reactRouting}", name="rp_index", defaults={"reactRouting": null})
     */
    public function index(): Response
    {
        $user = $this->getUser();

        // $rps = $this->getDoctrine()
        //     ->getRepository(Rp::class)
        //     ->findAll();

        if ($user) {

            $rps = $this->getDoctrine()
                ->getRepository(Rp::class)
                ->createQueryBuilder('r')
                ->select('r', 'a')
                ->join('r.appUser', 'a')
                ->where('a.id = :id')
                ->setParameter('id', $user->getId())
                ->getQuery()
                ->getResult();
        } else {
            $rps = "";
        }


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
            return $object->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonResponse = new JsonResponse();
        return $jsonResponse->setContent($serializer->serialize($rps, 'json'));
        // return $this->json($serializer->serialize($rps, 'json'));

    }

    /**
     * @Route("/", name="rp_new", methods="POST")
     */
    public function new_api(Request $request): Response
    {



        $data = $request->getContent();
        $jsonData = json_decode($data, true);

        $statut = $this->getDoctrine()->getRepository(Status::class)->find($jsonData["statut"]);
        $personnages = $this->getDoctrine()->getRepository(AppCharacter::class)->findBy(array(
            'id' => $jsonData["personnages"]));
        $userTemp = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository(AppUser::class)->find($userTemp);
        $em = $this->getDoctrine()->getManager();

        $rp = new Rp();
        $rp->setStatus($statut);
        $rp->setTitle($jsonData["titre"]);
        $rp->setLink($jsonData["lien"]);

        foreach ($personnages as $personnage) {
            $rp->addAppCharacter($personnage);
        }

        $rp->setAppUser($user);

        $em->persist($rp);
        $em->flush();

        return $this->json($this->serialize($rp));
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
     * @Route("/{id}/edit", name="rp_edit",  methods="GET|POST")
     */
    public function edit(Request $request, Rp $rp): Response
    {

        $data = $request->getContent();
        $jsonData = json_decode($data, true);

        $statut = $this->getDoctrine()->getRepository(Status::class)->find($jsonData["statut"]);
        $personnages = $this->getDoctrine()->getRepository(AppCharacter::class)->findBy(array(
            'id' => $jsonData["personnages"]));
        $userTemp = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository(AppUser::class)->find($userTemp);
        $em = $this->getDoctrine()->getManager();

        $rp->setStatus($statut);
        $rp->setTitle($jsonData["titre"]);
        $rp->setLink($jsonData["lien"]);

        $removeChar= $rp->getAppCharacter();

        foreach ($removeChar as $r) {
            $rp->removeAppCharacter($r);
        }

        foreach ($personnages as $personnage) {
            $rp->addAppCharacter($personnage);
        }

        $rp->setAppUser($user);

        $em->persist($rp);
        $em->flush();

        $rps = $this->getDoctrine()
            ->getRepository(Rp::class)->findAll();
        return $this->json($this->serialize($rps));
    }

    /**
     * @Route("/", name="rp_delete", methods={"DELETE"})
     */
    public function delete(Request $request): Response
    {

        $data = $request->getContent();
        $jsonData = json_decode($data, true);

        $rp = $this->getDoctrine()->getRepository(Rp::class)->find($jsonData["id"]);

        $em = $this->getDoctrine()->getManager();
        $em->remove($rp);
        $em->flush();

        return $this->json($this->serialize($rp));
    }
}
