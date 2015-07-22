<?php

namespace Sio\SemiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sio\SemiBundle\Entity\UserSeminar;
use Sio\SemiBundle\Form\UserSeminarType;

/**
 * UserSeminar controller.
 *
 * @Route("/admin/userseminar")
 */
class UserSeminarController extends Controller
{

    /**
     * Lists all UserSeminar entities.
     *
     * @Route("/", name="admin_userseminar")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SioSemiBundle:UserSeminar')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new UserSeminar entity.
     *
     * @Route("/", name="admin_userseminar_create")
     * @Method("POST")
     * @Template("SioSemiBundle:UserSeminar:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new UserSeminar();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_userseminar_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a UserSeminar entity.
     *
     * @param UserSeminar $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(UserSeminar $entity)
    {
        $form = $this->createForm(new UserSeminarType(), $entity, array(
            'action' => $this->generateUrl('admin_userseminar_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new UserSeminar entity.
     *
     * @Route("/new", name="admin_userseminar_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new UserSeminar();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a UserSeminar entity.
     *
     * @Route("/{id}", name="admin_userseminar_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SioSemiBundle:UserSeminar')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserSeminar entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing UserSeminar entity.
     *
     * @Route("/{id}/edit", name="admin_userseminar_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SioSemiBundle:UserSeminar')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserSeminar entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a UserSeminar entity.
    *
    * @param UserSeminar $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(UserSeminar $entity)
    {
        $form = $this->createForm(new UserSeminarType(), $entity, array(
            'action' => $this->generateUrl('admin_userseminar_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing UserSeminar entity.
     *
     * @Route("/{id}", name="admin_userseminar_update")
     * @Method("PUT")
     * @Template("SioSemiBundle:UserSeminar:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SioSemiBundle:UserSeminar')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserSeminar entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_userseminar_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a UserSeminar entity.
     *
     * @Route("/{id}", name="admin_userseminar_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SioSemiBundle:UserSeminar')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find UserSeminar entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_userseminar'));
    }

    /**
     * Creates a form to delete a UserSeminar entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_userseminar_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
