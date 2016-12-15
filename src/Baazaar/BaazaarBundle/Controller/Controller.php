<?php
namespace Baazaar\BaazaarBundle\Controller;

use Baazaar\BaazaarBundle\Entity\Ad;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

use Baazaar\MediaBundle\Entity\File;
use Baazaar\MediaBundle\Form\FileType;

class Controller extends BaseController {

    public function getSecurityContext() {
        return $this->container->get('security.authorization_checker');
    }

    public function enforceUserSecurity($role = 'ROLE_USER')
    {
        if (!$this->getSecurityContext()->isGranted($role)) {
            throw $this->createAccessDeniedException('message!');
        }
    }

    public function enforceOwnerSecurity(Ad $ad)
    {
        $user = $this->getUser();
        if ($user != $ad->getOwner()) {
            throw $this->createAccessDeniedException('You are not the owner!');
        }
    }

    public function createFile(&$file, $upload = null) {

        if(empty($upload)) $upload = $file->getFile();

        $file->setFilemime($upload->getMimeType());
        if(empty($file->getFilename())){
            $file->setFileName($upload->getClientOriginalName());
        }

        $file->setFilesize($upload->getSize());

        $file->setOwner($this->getUser());

        $mediaDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads';
        $file->setPath(sha1(uniqid(mt_rand(), true)) . '.' . $upload->guessExtension());
        $upload->move($mediaDir, $file->getPath());

        $em = $this->getDoctrine()->getManager();
        $em->persist($file);
        $em->flush();

        return $file;
    }

}
