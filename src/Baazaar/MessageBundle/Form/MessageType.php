<?php

namespace Baazaar\MessageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('body', TextareaType::class, array(
          'label' => 'Bericht',
          'attr' => array(
            'placeholder' => 'Type hier uw bericht...'
          )
        ));
    }

    public function getName() {
        return 'message';
    }
}
