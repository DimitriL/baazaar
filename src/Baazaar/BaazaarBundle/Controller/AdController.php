<?php
namespace Baazaar\BaazaarBundle\Controller;

use Baazaar\BaazaarBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Baazaar\BaazaarBundle\Entity\Ad;
use Baazaar\BaazaarBundle\Entity\Category;
use Baazaar\BaazaarBundle\Form\AdType;
use Baazaar\MediaBundle\Entity\File;
use Baazaar\BaazaarBundle\Entity\Price;

class AdController extends Controller {
    public function indexAction($slug) {
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('BaazaarBaazaarBundle:Ad')->findOneBy(array('slug' => $slug));

        if(!$ad) {
            throw $this->createNotFoundException('No ad with slug ' . $slug);
        }

        return $this->render('BaazaarBaazaarBundle:Ad:index.html.twig', array(
            "ad" => $ad
        ));
    }

    public function createAction(Request $request) {

        $this->enforceUserSecurity();

        $em = $this->getDoctrine()->getManager();

        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            //on a GET request, $form->isSubmitted() returns false.
            if($form->isValid() && $form->isSubmitted()) {
                $user = $this->getUser();

                foreach($ad->getUploads() as $upload) {
                    $file = new File();
                    $this->createFile($file, $upload);

                    //add file object to files property
                    $ad->getFiles()->add($file);
                }

                //only 1 possibilty at the moment
                $category = $em->getRepository('BaazaarBaazaarBundle:Category')->find($ad->getCategoriesList());
                $ad->addCategory($category);

                $ad->setOwner($user);
                $em->persist($ad);
                $em->flush();

                //add flash notice
                return $this->redirect($this->generateUrl('baazaar_baazaar_homepage')); //redirect to homepage
            }
        }

        return $this->render('BaazaarBaazaarBundle:Ad:create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('BaazaarBaazaarBundle:Ad')->find($id);

        if(!$ad) {
            throw $this->createNotFoundException('No ad with id ' . $id);
        }
        //check if user is owner
        $this->enforceOwnerSecurity($ad);

        //remove ad
        $em->remove($ad);
        $em->flush();

        //add flash notice
        return $this->redirect($this->generateUrl('baazaar_baazaar_homepage')); //redirect to homepage
    }

    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('BaazaarBaazaarBundle:Ad')->find($id);

        if(!$ad) {
            throw $this->createNotFoundException('No ad with id ' . $id);
        }
        //check if user is owner
        $this->enforceOwnerSecurity($ad);
        $form = $this->createForm( AdType::class, $ad);

        return $this->render('BaazaarBaazaarBundle:Ad:edit.html.twig', array(
            'form' => $form->createView(),
            'ad' => $ad
        ));
    }

    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('BaazaarBaazaarBundle:Ad')->find($id);

        if(!$ad) {
            throw $this->createNotFoundException('No ad with id ' . $id);
        }
        //check if user is owner
        $this->enforceOwnerSecurity($ad);

        $ad->setTitle($request->get('ad')['title']);
        $ad->setBody($request->get('ad')['body']);
        $em->flush();

        return $this->redirect($this->generateUrl('baazaar_baazaar_ad_edit', array('id' => $ad->getId())));
    }

    public function markFavoriteAction($id) {
      $em = $this->getDoctrine()->getManager();
      $ad = $em->getRepository('BaazaarBaazaarBundle:Ad')->find($id);

      $user = $this->getUser();
      $user->addFavoriteAd($ad);

      $em->persist($user);
      $em->flush();


    return $this->redirect($this->generateUrl('baazaar_baazaar_ad', array('slug' => $ad->getSlug())));

    }
}
