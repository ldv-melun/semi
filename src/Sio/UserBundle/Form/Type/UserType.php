<?php

namespace Sio\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of RegistrationFormType
 *
 * @author kpu
 */
class UserType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('lastName');
    $builder->add('firstName');
//    $builder->add('pass1', 'password');
//    $builder->add('pass2', 'password');
    $builder->add('homeCity');
    $builder->add('organisation', 'entity', //new OrganisationFormType());
     array('class'=> 'SioSemiBundle:Organisation', 'property' => 'name' ));
 
    
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
        'data_class' => 'Sio\UserBundle\Entity\User',
        'cascade_validation' => true,
    ));
  }

  public function getName() {
    return 'sio_user';
  }

}
