<?php

namespace Sio\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

/**
 * Description of UserType
 *
 * @author kpu
 */
class RegistrationFormType extends BaseType /*AbstractType*/ {

    //private $allStatusUserSeminar;
    //private $idStatusUserSeminarChecked;
    protected $helperRegistration;    
    /*
    public function __construct($allStatusUserSeminar, $idStatusUserSeminarChecked = null)
    {
      $this->allStatusUserSeminar = $allStatusUserSeminar;
      $this->idStatusUserSeminarChecked = $idStatusUserSeminarChecked;
    }
*/
/*            
    public function __construct(ContainerInterface $container)
    {
     $this->container = $container;
     $helper = $this->container->get('helper.registration');
     $status = $helper->getStatusUserSeminar();
     $this->allStatusUserSeminar = $status['allStatusUserSeminar'];
     $this->idStatus = $status['idStatus'];
    }
  */
 
 public function __construct($class, \Sio\UserBundle\Service\HelperRegistration $helper)
 {
   parent::__construct($class);
   $this->helperRegistration = $helper;
 }
   
 public function buildForm(FormBuilderInterface $builder, array $options) {
    parent::buildForm($builder, $options);
    $builder->remove('username');
    
    $builder->add('lastName');
    $builder->add('firstName');
//    $builder->add('pass1', 'password', array('mapped' => false));
//    $builder->add('pass2', 'password', array('mapped' => false));
    $builder->add('homeCity');
    $builder->add('organisation', 'entity', 
     array('class'=> 'SioSemiBundle:Organisation', 'property' => 'name' ));
    
    // get info status possible for user in this seminar
    $status = $this->helperRegistration->getStatusUserSeminar();
    $allStatusUserSeminar = $status['allStatusUserSeminar'];
    $currentIdStatusUserSeminar = $status['idStatus'];

    if ($allStatusUserSeminar) :     
      $builder->add('status', 'choice', array(
        'choices'   => $allStatusUserSeminar,
        'required'  => false,
        'mapped' => false,
        'expanded'=> TRUE,
        'multiple'=> FALSE,  
        'required' => TRUE,
        'data' => $currentIdStatusUserSeminar
      ));
    endif;
    $builder->add('Valider', 'submit');    
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
        'data_class' => 'Sio\UserBundle\Entity\User',
        'cascade_validation' => true,
        'validation_groups' => array('registration'),
    ));
  }

  public function getName() {
    return 'sio_user_registration';
  }

}
