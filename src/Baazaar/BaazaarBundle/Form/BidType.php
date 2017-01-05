<?php

namespace Baazaar\BaazaarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BidType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('amount', null, array(
          'label' => false,
          'attr' => array(
            'placeholder' => 'Bedrag'
          )
        ));
    }

    public function getName() {
        return 'bid';
    }
}
