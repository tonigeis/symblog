<?php

namespace Blogger\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Blogger\BlogBundle\Entity\Blog;
use Blogger\BlogBundle\Form\BlogType;

/**
 * Blog controller.
 *
 */
class AdminBlogController extends Controller
{
    /**
     * Lists all Blog entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')->findAll();

        return $this->render('BloggerBlogBundle:Blog:adminIndex.html.twig', array(
            'blogs' => $blogs,
        ));
    }

    /**
     * Creates a new Blog entity.
     *
     */
    public function newAction(Request $request)
    {
        $blog = new Blog();
        $form = $this->createForm('Blogger\BlogBundle\Form\BlogType', $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('adminblog_show', array('id' => $blog->getId()));
        }

        return $this->render('BloggerBlogBundle:Blog:adminNew.html.twig', array(
            'blog' => $blog,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Blog entity.
     *
     */
    public function showAction(Blog $blog)
    {
        $deleteForm = $this->createDeleteForm($blog);

        return $this->render('BloggerBlogBundle:Blog:adminShow.html.twig', array(
            'blog' => $blog,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Blog entity.
     *
     */
    public function editAction(Request $request, Blog $blog)
    {
        $deleteForm = $this->createDeleteForm($blog);
        $editForm = $this->createForm('Blogger\BlogBundle\Form\BlogType', $blog);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('adminblog_edit', array('id' => $blog->getId()));
        }

        return $this->render('BloggerBlogBundle:Blog:adminEdit.html.twig', array(
            'blog' => $blog,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Blog entity.
     *
     */
    public function deleteAction(Request $request, Blog $blog)
    {
        $form = $this->createDeleteForm($blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blog);
            $em->flush();
        }

        return $this->redirectToRoute('adminblog_index');
    }

    /**
     * Creates a form to delete a Blog entity.
     *
     * @param Blog $blog The Blog entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Blog $blog)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('adminblog_delete', array('id' => $blog->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
