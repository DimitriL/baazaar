<?php

namespace Baazaar\BaazaarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdReportType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('reason', TextareaType::class);
    }

    public function getName() {
        return 'adReport';
    }
}
