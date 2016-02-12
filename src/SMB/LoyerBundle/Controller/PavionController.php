<?php

namespace SMB\LoyerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SMB\LoyerBundle\Entity\Pavion;
use SMB\LoyerBundle\Form\PavionType;

/**
 * Pavion controller.
 *
 * @Route("/gestion-loyer/pavion")
 */
class PavionController extends Controller
{

    /**
     * Lists all Pavion entities.
     *
     * @Route("/", name="gestion-loyer_pavion")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SMBLoyerBundle:Pavion')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Pavion entity.
     *
     * @Route("/", name="gestion-loyer_pavion_create")
     * @Method("POST")
     * @Template("SMBLoyerBundle:Pavion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Pavion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('gestion-loyer_pavion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Pavion entity.
     *
     * @param Pavion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Pavion $entity)
    {
        $form = $this->createForm(new PavionType(), $entity, array(
            'action' => $this->generateUrl('gestion-loyer_pavion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Pavion entity.
     *
     * @Route("/new", name="gestion-loyer_pavion_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Pavion();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Pavion entity.
     *
     * @Route("/{id}", name="gestion-loyer_pavion_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SMBLoyerBundle:Pavion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pavion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Pavion entity.
     *
     * @Route("/{id}/edit", name="gestion-loyer_pavion_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SMBLoyerBundle:Pavion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pavion entity.');
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
    * Creates a form to edit a Pavion entity.
    *
    * @param Pavion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Pavion $entity)
    {
        $form = $this->createForm(new PavionType(), $entity, array(
            'action' => $this->generateUrl('gestion-loyer_pavion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Pavion entity.
     *
     * @Route("/{id}", name="gestion-loyer_pavion_update")
     * @Method("PUT")
     * @Template("SMBLoyerBundle:Pavion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SMBLoyerBundle:Pavion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pavion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('gestion-loyer_pavion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Pavion entity.
     *
     * @Route("/{id}", name="gestion-loyer_pavion_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SMBLoyerBundle:Pavion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pavion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('gestion-loyer_pavion'));
    }

    /**
     * Creates a form to delete a Pavion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gestion-loyer_pavion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
