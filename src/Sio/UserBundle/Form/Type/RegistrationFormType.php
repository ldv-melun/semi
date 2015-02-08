<?php

namespace Sio\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of RegistrationFormType
 *
 * @author kpu
 */
class RegistrationFormType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       // add your custom field
       // $builder->add('name');
       // $builder->remove('username');
       // 
       // ... insignificant, because not use !
       // @see RegistrationController.php (is redefined that of FOSUser)
       //  
       // so this file is it still useful ? 
    }

    
    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'sio_user_registration';
    }
}