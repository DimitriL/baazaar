<?php
namespace Baazaar\LocationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Baazaar\LocationBundle\Entity\Place;

class LocationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('id', HiddenType::class);

        $formModifier = function (\Symfony\Component\Form\Form $form, Place $place = null) {

        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier){
              $data = $event->getData();
              $formModifier($event->getForm(), new Place());
            }
        );


        $builder->addEventListener(
          FormEvents::POST_SUBMIT,
          function (FormEvent $event) use ($formModifier) {

              // It's important here to fetch $event->getForm()->getData(), as
              // $event->getData() will get you the client data (that is, the ID)
              $place_id = $event->getForm()->getData();

              $place = new Place();
              $place->setId($place_id);

              // since we've added the listener to the child, we'll have to pass on
              // the parent to the callback functions!
              $formModifier($event->getForm(), $place);
          }
        );

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
