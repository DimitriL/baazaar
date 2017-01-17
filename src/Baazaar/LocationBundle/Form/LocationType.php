<?php
namespace Baazaar\LocationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class LocationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('id', HiddenType::class);
    }

    /**
    * @param OptionsResolverInterface $resolver
    */
   public function setDefaultOptions(OptionsResolverInterface $resolver)
   {
       $resolver->setDefaults(array(
           'data_class' => 'Baazaar\LocationBundle\Entity\Place'
       ));
   }


    public function getName() {
        return 'place';
    }
}
