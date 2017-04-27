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

class VoteController extends Controller
{
    /**
     * @Route("/vote/{id_user}/{id_link}/{like}", name="vote")
     */
    public function voteAction($id_user, $id_link, $like)
    {
      $message = null;
      $repository = $this->getDoctrine()->getRepository('AppBundle:User');
      $selectUser = $repository->findOneById($id_user);
      //Si on trouve l'user
      if ($selectUser) {
          //var_char('selectuser');
        $repository = $this->getDoctrine()->getRepository('AppBundle:Link');
        $selectLink = $repository->findOneById($id_link);
        //Si on trouve le lien
        if ($selectLink) {
            //var_char('selectlink');
            $repository = $this->getDoctrine()->getRepository('AppBundle:Vote');
            $selectVote = $repository->findOneBy(array(
                'user' => $selectUser,
                'link' => $selectLink
            ));
            $em = $this->getDoctrine()->getManager();
            //Si on a déjà voté pour le lien
            if (!$selectVote) {
                //var_char('selectvote');
                $selectVote = new Vote();
                $selectVote->setUser($selectUser);
                $selectVote->setLink($selectLink);
                $selectVote->setVoteLike($like);
                // tells Doctrine you want to (eventually) save the Product (no queries yet)
                $em->persist($selectVote);
                // actually executes the queries (i.e. the INSERT query)
                $message = 'Vautre vöte à éter comptabilisai. MDRrrr, xDeyy!';
            } else {
                // update vote
                $message =  'Vs avait daijà voter pr seux li1.';
                $selectVote->setVoteLike($like);
            }
            $em->flush();
        }//if ($selectLink)
      }//if ($selectUser)
      else {
        $message = 'Auqune dauners neux cauresponts.';
      }

      // return redirectroute to listlink
      return $this->redirectToRoute('listLinks');
    }
    /**
     * @Route("/countVote/{id_link}", name="countVote")
     */

    public function totalVoteAction($id_link)
    {
      //Calcul le nombre de vote
      $em = $this->getDoctrine()->getManager();
      $qb = $em->createQueryBuilder();
      $totalVote = $qb->select('COUNT(v)')
                  ->from('AppBundle:Vote' , 'v')
                  ->where('v.link = :id')
                  ->setParameter('id', (int)$id_link)
                  ->getQuery()
                  ->getOneOrNullResult();
      $totalVote1 = $totalVote[1];
      var_dump($totalVote1);
      //Calcul du nombre de like
      $qbb = $em->createQueryBuilder();
      $totalLike = $qbb->select('COUNT(w)')
                  ->from('AppBundle:Vote' , 'w')
                  ->where('w.link = :id')
                  ->andWhere('w.like = :like')
                  ->setParameter('id', (int)$id_link)
                  ->setParameter('like', 1)
                  ->getQuery()
                  ->getOneOrNullResult();
      $totalLike1 = $totalLike[1];
      var_dump($totalLike1);
      //Calcul du nombre de dislike
      $qbbb = $em->createQueryBuilder();
      $totalDislike = $qbbb->select('COUNT(w)')
                  ->from('AppBundle:Vote' , 'w')
                  ->where('w.link = :id')
                  ->andWhere('w.like = :like')
                  ->setParameter('id', (int)$id_link)
                  ->setParameter('like', 0)
                  ->getQuery()
                  ->getOneOrNullResult();
      $totalDislike1 = $totalDislike[1];
      var_dump($totalDislike1);
      //Calcul du score
      $score = ((int)$totalLike1 - (int)$totalDislike1);
      var_dump($score);
      //Calcul position
      $position = number_format((((int)$score / (int)$totalVote1) * 100), 2, '.', ' ');
      var_dump($position);
    }

    /**
     * @Route("/deletevote/{id_user}/{id_link}", name="deleteVote")
     */
    public function deleteVoteAction($id_user, $id_link)
    {
      $message = null;
      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('AppBundle:Vote');
      try {
        $delete = $repository->findOneBy(array('user' => $id_user, 'link' => $id_link));
        if (!$delete) {
          throw new \Exception();
        }
        $em->remove($delete);
        $em->flush();
        $message = 'Le vote a été supprimé.';
      } catch (\Exception $e) {
        $message = 'Aucune correspondence dans la base de donnée.';
      }
      var_dump($message);
    }

    /**
     * @Route("/updatevote/{id_user}/{id_link}/{bool}", name="updateVote")
     */
     public function updateVoteAction($id_user, $id_link, $bool)
     {
        $em = $this->getDoctrine()->getManager();
        $updateVote = $em->getRepository('AppBundle:Vote')
            ->findOneBy(array('user' => $id_user, 'link' => $id_link));

        $updateVote->setVoteLike($bool);
        $em->flush();
     }

     /**
     * @Route("/listvotes", name="listVotes")
     */
    public function listVotesAction()
    {
      $em = $this->getDoctrine()->getManager();
      $Repository = $this->getDoctrine()->getRepository('AppBundle:Vote');
      $allVotes = $Repository->findAll();
      $stack = array();
      foreach($allVotes as $vote)
      {
        $status = $vote->getVoteLike() ? 'Like' : 'Dislike';
        array_push($stack, array(
          'user' => $vote->getUser()->getName(),
          'url' => $vote->getLink()->getUrl(),
          'status' => $status
        ));
      }
      return $this->render('vote/listvotes.html.twig', [
          'page_title' => 'Votes',
          'votes' => $stack,
      ]);
    }
}
