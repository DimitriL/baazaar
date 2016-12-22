<?php
namespace Baazaar\BaazaarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Baazaar\BaazaarBundle\Entity\Price;

class PriceType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('price_type', ChoiceType::class, array(
          'choices' => $this->getPriceTypes(),
          'data_class' => null,
          'required' => true
        ));

        //$builder->add('amount', TextType::class);
        //$builder->add('accept_bidding', CheckboxType::class);
        //$builder->add('minimum_bidding_amount', TextType::class);


        $formModifier = function (\Symfony\Component\Form\Form $form, Price $price = null) {
            switch($price->getPriceType()) {
                case 'amount':
                    $form->add('amount', TextType::class);
                    $form->add('accept_bidding', CheckboxType::class);
                    break;
                case 'consent':
                    $form->add('amount', TextType::class);
                    break;
            }
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier){
              $data = $event->getData();
              $formModifier($event->getForm(), new Price());
            }
        );


        $builder->get('price_type')->addEventListener(
          FormEvents::POST_SUBMIT,
          function (FormEvent $event) use ($formModifier) {

              // It's important here to fetch $event->getForm()->getData(), as
              // $event->getData() will get you the client data (that is, the ID)
              $price_type = $event->getForm()->getData();

              $price = new Price();
              $price->setPriceType($price_type);

              // since we've added the listener to the child, we'll have to pass on
              // the parent to the callback functions!
              $formModifier($event->getForm()->getParent(), $price);
          }
        );
    }

    /**
    * @param OptionsResolverInterface $resolver
    */
   public function setDefaultOptions(OptionsResolverInterface $resolver)
   {
       $resolver->setDefaults(array(
           'data_class' => 'Baazaar\BaazaarBundle\Entity\Price'
       ));
   }

    protected function getPriceTypes() {
      return array(
          '-- select --' => null,
          'amount' => 'amount',
          'consent' => 'consent',
          'free' => 'free',
          'trade' => 'trade'
      );
    }
    public function getName() {
        return 'price';
    }
}
