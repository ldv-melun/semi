<?php
/*
 * Copyright (C) 2015 -- (BTS Sio National French Education)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * Usage example:  php app/console sio:semi:users admin@net.fr 1234
 *
 * 
 */

namespace Sio\SemiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\Console\Output\OutputInterface;
use Sio\SemiBundle\Entity\User;

/**
 * Description of SemiUserCommand
 * @see http://symfony.com/fr/doc/current/cookbook/console/console_command.html
 * 
 */
class SemiUserCommand extends ContainerAwareCommand {
  
  protected function configure()
    {
        $this
            ->setName('sio:semi:users')
            ->setDescription('Add Semi users')
            ->addArgument('mail', InputArgument::REQUIRED, 'The mail')
            ->addArgument('password', InputArgument::REQUIRED, 'The password')
            ->addArgument('roles', InputArgument::REQUIRED, 'The roles')    
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userMail = $input->getArgument('mail');
        $password = $input->getArgument('password');
        $userRoles = $input->getArgument('roles');
        
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
 
        $user = new User();
        $user->setMail($userMail);

        // encode the password
        $factory = $this->getContainer()->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($encodedPassword);
        
        if ($userRoles == "ROLE_ANONYMOUS" OR $userRoles == "ROLE_USER" OR $userRoles == "ROLE_MANAGER" OR $userRoles == "ROLE_ADMIN" ) {
            $user->setRoles($userRoles);
        } else {
            echo "Valeur non reconnus par le système";
        }
        $em->persist($user);
        $em->flush();

        $output->writeln(sprintf('Added user with %s mail, password %s and %s roles', $userMail, $password, $userRoles));
    }
}
