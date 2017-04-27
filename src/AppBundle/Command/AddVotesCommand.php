<?php
// src/AppBundle/Command/AddVotesCommand.php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\Entity\User;
use AppBundle\Entity\Link;
use AppBundle\Entity\Vote;


class AddVotesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
      $this
          // the name of the command (the part after "bin/console")
          ->setName('app:add-votes')

          // the short description shown while running "php bin/console list"
          ->setDescription('Add 500 votes in the database.')

          // the full command description shown when running the command with
          // the "--help" option
          ->setHelp('This command allows you to add 500 votes in the database with random data for testing purpose.')
      ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $em = $this->getContainer()->get('doctrine.orm.entity_manager');
      $userCount = $em->createQuery('SELECT COUNT(u.id) FROM AppBundle\Entity\User u')->getSingleScalarResult();
      $linkCount = $em->createQuery('SELECT COUNT(u.id) FROM AppBundle\Entity\Link u')->getSingleScalarResult();

      for($i = 0; $i < 500; $i++)
      {
        $offset = rand(0, $userCount - 1);
        $query = $em->createQuery('SELECT DISTINCT u FROM AppBundle\Entity\User u')
                ->setMaxResults(1)
                ->setFirstResult($offset);
        $result = $query->getResult();
        $user = $result[0];

        $offset = rand(0, $linkCount - 1);
        $query = $em->createQuery('SELECT DISTINCT u FROM AppBundle\Entity\Link u')
                ->setMaxResults(1)
                ->setFirstResult($offset);
        $result = $query->getResult();
        $link = $result[0];

        $voteLike = (rand(0, 4) > 0);

        $vote = new Vote();
        $vote->setUser($user);
        $vote->setLink($link);
        $vote->setVoteLike($voteLike);
        $em->persist($vote);
      }
      $em->flush();
    }
  }
