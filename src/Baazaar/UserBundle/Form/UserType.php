<?php
namespace Baazaar\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('username');
        $builder->add('email', 'email');
        $builder->add('plainPassword', 'repeated', array('type' =>  'password'));    
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
           'data_class' => 'Baazaar\UserBundle\Entity\User' 
        ));
    }
    
    public function getName() {
        return 'user_register';
    }
}

