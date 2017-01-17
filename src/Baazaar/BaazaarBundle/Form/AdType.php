<?php

namespace Baazaar\BaazaarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Baazaar\BaazaarBundle\Form\DataTransformer\LocationToIdsTransformer;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Baazaar\BaazaarBundle\Form\PriceType;
use Baazaar\LocationBundle\Form\LocationType;

class AdType extends AbstractType {

    private $catRepository;

    public function __construct(Doctrine $doctrine) {
        $this->catRepository = $doctrine->getManager()->getRepository('BaazaarBaazaarBundle:Category');
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title');
        $builder->add('body', TextareaType::class);
        $builder->add('categoriesList', ChoiceType::class, array(
            'choices' => $this->getCategories(),
            'data_class' => null
        ));
        $builder->add('uploads', FileType::class, array(
            'multiple' => true,
            'data_class' => null
        ));

        $builder->add('object_status', ChoiceType::class, array(
          'choices' => $this->getObjectStatusses(),
          'data_class' => null
        ));

        $builder->add('delivery_method', ChoiceType::class, array(
          'choices' => $this->getDeliveryMethods(),
          'data_class' => null
        ));

        $builder->add('locations', TextType::class, array(
          'mapped' => false
        ));

        $builder->add('location', HiddenType::class, array(
          'mapped' => false
        ));

        $builder->add('price', PriceType::class, array(
          'data_class' => 'Baazaar\BaazaarBundle\Entity\Price' //this is needed to convert form array to correct object
        ));
    }

    private function getCategories() {
        $categories = $this->catRepository->childrenHierarchy();
        return $this->buildTreeChoices($categories);
    }

    protected function buildTreeChoices( $choices , $level = 0 ) {
        $result = array();
        foreach ( $choices as $choice ){
            $result[str_repeat( '-' , $level ) . ' ' . $choice['title']] = $choice['id'];

            if ( !empty($choice['__children']) ) {
                $result = $result + $this->buildTreeChoices( $choice['__children'] , $level + 1 );
            }
        }
        return $result;
    }

    /**
    * @param OptionsResolverInterface $resolver
    */
   public function setDefaultOptions(OptionsResolverInterface $resolver)
   {
       $resolver->setDefaults(array(
           'data_class' => 'Baazaar\BaazaarBundle\Entity\Ad'
       ));
   }

    protected function getObjectStatusses() {
      return array(
          'new' => 'new',
          'used' => 'used'
      );
    }

    protected function getDeliveryMethods() {
      return array(
          'send' => 'send',
          'pickup' => 'pickup'
      );
    }

    public function getName() {
        return 'ad';
    }
}
