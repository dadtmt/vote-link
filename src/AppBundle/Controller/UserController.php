<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Vote;
use AppBundle\Entity\User;
use AppBundle\Entity\Link;

class DefaultController extends Controller
{
    /**
     * @Route("/listusers", name="listUsers")
     */
    public function listUsersAction()
    {
      $em = $this->getDoctrine()->getManager();
      $repository = $this->getDoctrine()->getRepository('AppBundle:User');
      $allUsers = $repository->findAll();
      $stack = array();
      foreach($allUsers as $usr)
      {
        $likesCount = $em->createQuery('SELECT COUNT(v.id) FROM AppBundle\Entity\Vote v JOIN v.user u WHERE (u.id = ' . $usr->getId() . ' AND v.voteLike = true)' )->getSingleScalarResult();
        $dislikesCount = $em->createQuery('SELECT COUNT(v.id) FROM AppBundle\Entity\Vote v JOIN v.user u WHERE (u.id = ' . $usr->getId() . ' AND v.voteLike = false)' )->getSingleScalarResult();
        array_push($stack, array(
          'name' => $usr->getName(),
          'email' => $usr->getEmail(),
          'likes' => $likesCount,
          'dislikes' => $dislikesCount,
          'votes' => $likesCount + $dislikesCount,
        ));
      }
      return $this->render('default/listusers.html.twig', [
          'page_title' => 'Users',
          'users' => $stack,
      ]);
    }
}
