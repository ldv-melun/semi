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
class OrganisationFormType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
     $builder->add('name');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sio\SemiBundle\Entity\Organisation',
        ));
    }
    
    public function getName()
    {
        return 'organisation';
    }
}