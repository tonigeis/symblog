<?php
// src/Blogger/BlogBundle/Controller/PageController.php

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;

class PageController extends Controller
{
    public function indexAction()
    {  
        $em = $this->getDoctrine()
                   ->getEntityManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
                    ->getLatestBlogs();

        return $this->render('BloggerBlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs
        ));
    }

    public function aboutAction(){
    	return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }

    public function contactAction()
	{
		$enquiry = new Enquiry();
	    $form = $this->createForm(new EnquiryType(), $enquiry);

	    $request = $this->getRequest();

	    if ($request->getMethod() == 'POST') {
	        $form->handleRequest($request);

	        if ($form->isValid()) {
	            // realiza alguna acción, como enviar un correo electrónico

	            // Redirige - Esto es importante para prevenir que el usuario
	            // reenvíe el formulario si actualiza la página
	            return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));
	        }
	    }

	    return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
	        'form' => $form->createView()
	    ));
	}

	public function sidebarAction()
	{
	    $em = $this->getDoctrine()
	               ->getEntityManager();

	    $tags = $em->getRepository('BloggerBlogBundle:Blog')
	               ->getTags();

	    $tagWeights = $em->getRepository('BloggerBlogBundle:Blog')
	                     ->getTagWeights($tags);

	    $commentLimit   = $this->container->getParameter('blogger_blog.comments.latest_comment_limit');

	    $latestComments = $em->getRepository('BloggerBlogBundle:Comment')
	                         ->getLatestComments($commentLimit);

	    return $this->render('BloggerBlogBundle:Page:sidebar.html.twig', array(
	        'latestComments'    => $latestComments,
	        'tags'              => $tagWeights
	    ));
	}

	public function tagAction($tag)
    {  
        $em = $this->getDoctrine()
                   ->getEntityManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
                    ->getBlogsWithTag($tag);

        return $this->render('BloggerBlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs
        ));
    }
}