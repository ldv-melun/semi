<?php

namespace Sio\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of UserType
 *
 * @author kpu
 */
class RegistrationFormType extends AbstractType {

    private $allStatusUserSeminar;
    private $idStatusUserSeminarChecked;
    protected $container;    
    /*
    public function __construct($allStatusUserSeminar, $idStatusUserSeminarChecked = null)
    {
      $this->allStatusUserSeminar = $allStatusUserSeminar;
      $this->idStatusUserSeminarChecked = $idStatusUserSeminarChecked;
    }
*/
            
    public function __construct(ContainerInterface $container)
    {
     $this->container = $container;
     $helper = $this->container->get('helper.registration');
     $status = $helper->getStatusUserSeminar();
     $this->allStatusUserSeminar = $status['allStatusUserSeminar'];
     $this->idStatus = $status['idStatus'];
    }
  
 
 
   public function setContainer($container){
    $this->container = $container;
   }
  
   public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('lastName');
    $builder->add('firstName');
    $builder->add('pass1', 'password', array('mapped' => false));
    $builder->add('pass2', 'password', array('mapped' => false));
    $builder->add('homeCity');
    $builder->add('organisation', 'entity', 
     array('class'=> 'SioSemiBundle:Organisation', 'property' => 'name' ));
     
    if ($this->allStatusUserSeminar) :     
      $builder->add('status', 'choice', array(
        'choices'   => $this->allStatusUserSeminar,
        'required'  => false,
        'mapped' => false,
        'expanded'=> TRUE,
        'multiple'=> FALSE,  
        'required' => TRUE,
        'data' => $this->idStatusUserSeminarChecked  
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
    return 'sio_user';
  }

}
