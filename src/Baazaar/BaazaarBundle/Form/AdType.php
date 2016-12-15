<?php

namespace Baazaar\BaazaarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

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

    public function getName() {
        return 'ad';
    }
}
