<?php

namespace App\Controller;

use App\Entity\Rp;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        if ($user) {
            $rps = $this->getDoctrine()
                ->getRepository(Rp::class)
                ->createQueryBuilder('r')
                ->select('COUNT(r)')
                ->join('r.appUser', 'a')
                ->where('a.id = :id')
                ->setParameter('id', $user->getId())
                ->getQuery()
                ->getSingleScalarResult();
        } else {
            $rps = 0;
        }

        return $this->render('default/index.html.twig', ['rps' => $rps,]);
    }

    /**
     * @Route("/data", name="data")
     */
    public function dataAction()
    {
        return new JsonResponse([
            [
                'id' => 1,
                'author' => 'Chris Colborne',
                'avatarUrl' => 'http://1.gravatar.com/avatar/13dbc56733c2cc66fbc698cdb07fec12',
                'title' => 'Bitter Predation',
                'description' => 'Thirteen thin, round towers form an almost perfectly squared barrier around this marvelous castle and are connected by big, thin walls made of light pink stone. Rough windows are scattered thinly around the walls in fairly symmetrical patterns, along with overhanging crenelations for archers and artillery.',
            ],
            [
                'id' => 2,
                'author' => 'Louanne Perez',
                'avatarUrl' => 'https://randomuser.me/api/portraits/thumb/women/18.jpg',
                'title' => 'Strangers of the Ambitious',
                'description' => "A huge gate with thick metal doors, a regular bridge and large crenelations offers a warm haven within these cold, isolated landsand it's the only way in, at least to those unfamiliar with the castle and its surroundings.",
            ],
            [
                'id' => 3,
                'author' => 'Theodorus Dietvorst',
                'avatarUrl' => 'https://randomuser.me/api/portraits/thumb/men/49.jpg',
                'title' => 'Outsiders of the Mysterious',
                'description' => "Plain fields of a type of grass cover most of the fields outside of the castle, adding to the castle's aesthetics. This castle is relatively new, but so far it stood its ground with ease and it'll likely do so for ages to come.",
            ],
        ]);
    }
}
