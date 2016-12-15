<?php

namespace Baazaar\MediaBundle\Controller;

use Baazaar\BaazaarBundle\Controller\Controller;
use Baazaar\MediaBundle\Entity\File;
use Baazaar\MediaBundle\Form\FileType;
use Symfony\Component\HttpFoundation\Request;

class MediaController extends Controller{
    function createAction(Request $request) {
        
        $this->enforceUserSecurity();
        
        $file = new File();
        $form = $this->createForm(new FileType(), $file);
                        
        $form->handleRequest($request);
        
        //on a GET request, $form->isSubmitted() returns false.
        if($form->isValid() && $form->isSubmitted()) {
            $this->createFile($file);
            
            //add flash notice
            return $this->redirect($this->generateUrl('baazaar_media_create')); //redirect to homepage
        }               
        return $this->render('BaazaarMediaBundle:media:create.html.twig', array(
            'form' => $form->createView()
        ));
    }
}