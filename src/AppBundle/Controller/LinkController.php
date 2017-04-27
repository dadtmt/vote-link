<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Link;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Embed\Embed;
use AppBundle\ImageSave\ImageSave;


class LinkController extends Controller
{
  /**
   * @Route("/recup-url", name="recupurlpage")
   */
  public function recupUrlAction(Request $request)
  {
    //create an entity to use with form
    $link = new Link();
    // create Form builder
    $formBuilder = $this->createFormBuilder($link);
    //add fields
    $formBuilder->add('url', UrlType::class);
    $formBuilder->add('enregistrer', SubmitType::class);
    // get the formBuilder
    $form = $formBuilder->getForm();

    $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // $form->getData() holds the submitted values
             // but, the original `$task` variable has also been updated
             $link = $form->getData();
             //Create the "embed" of the link
             $info = Embed::create($link->getUrl());
             //get the info of the URL
             $title = $info->title;
             $description = $info->description;
             $type = $info->type;
             $image = $info->image;
             $format = 'd-m-Y';
             $publishedDate = date("d-m-Y");
             $publishedTime = \DateTime::createFromFormat($format, $publishedDate);
             //set
             $link->setTitle($title);
             $link->setDescription($description);
             $link->setType($type);
             $link->setImage($image);
             $LinkSave = ImageSave::save($link->getImage());
             $link->setPublishedDate($publishedTime);
             //Save the URL to BDD
             $em = $this->getDoctrine()->getManager();
             $em->persist($link);
             $em->flush();

             return $this->render('link/resultat.html.twig',
             [
                  'url' => $link->getURL(),
                  'image' => $link->getImage(),
                  'description' => $link->getDescription(),
                  'title' => $link->getTitle(),
              ]);
          }

      return $this->render('link/recup-url.html.twig',
      [
          'form' => $form->createView(),
      ]);
  }

}
