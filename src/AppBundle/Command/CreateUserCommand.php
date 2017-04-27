<?php


// src/AppBundle/Command/CreateUserCommand.php
namespace AppBundle\Command;

use AppBundle\Entity\Link;
use AppBundle\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\Mapping as ORM;



class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
      $this
    // the name of the command (the part after "bin/console")
    ->setName('app:create-user')

    // the short description shown while running "php bin/console list"
    ->setDescription('Creates a new user.')

    // the full command description shown when running the command with
    // the "--help" option
    ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      // outputs multiple lines to the console (adding "\n" at the end of each line)
      $output->writeln([
        '','','','Le CMD c\'est de type chiant.','','','',
      ]);

      $name='Test';

      // outputs a message followed by a "\n"
      $output->writeln($name);

      //get the doctrine entity management
       $em = $this->getContainer()->get('doctrine')->getManager();

      for ($i=1; $i < 100; $i++) {
        // Auto remplissage table User
        $user = new User();
        $user->setName($name.$i);
        $user->setEmail('test@test.'.$i);

         $em->persist($user);
         // Auto remplissage table User ->fin

         // Auto remplissage table Link
         $link = new Link();
         $link->setUrl('htt://TEST'.$i.'.com');
         $em->persist($link);
         //Auto remplissage table Link ->Fin
      }
       $em->flush();
       $output->writeln('Regarde ta Database maintenant mon cochon');
    }
}
