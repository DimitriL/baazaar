<?php


namespace Baazaar\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FileType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('filename');
        $builder->add('file', 'file', array('label' => 'Upload media'));
        
    }
    
    public function getName() {
        return 'file';
    }
}