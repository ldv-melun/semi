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
    
    }

    public function getName()
    {
        return 'sio_semi_user_registration';
    }
}