<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 5.9.16
 * Time: 16.26
 */

namespace Bloger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Bloger\BlogBundle\Entity\Comment;
use Bloger\BlogBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Comment controller.
 */
class CommentController extends Controller
{
    public function newAction($blog_id)
    {
        $blog = $this->getBlog($blog_id);

        $comment = new Comment();
        $comment->setBlog($blog);
        $form   = $this->createForm(CommentType::class, $comment);

        return $this->render('BlogerBlogBundle:Comment:form.html.twig', array(
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }

    public function createAction(Request $request, $blog_id)
    {
        $blog = $this->getBlog($blog_id);

        $comment  = new Comment();
        $comment->setBlog($blog);
        $form    = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()
                ->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('BlogerBlogBundle_blog_show', array(
                    'id'    => $comment->getBlog()->getId(),
                    'slug'  => $comment->getBlog()->getSlug())) .
                '#comment-' . $comment->getId()
            );
        }

        return $this->render('BlogerBlogBundle:Comment:create.html.twig', array(
            'comment' => $comment,
            'form'    => $form->createView()
        ));
    }

    protected function getBlog($blog_id)
    {
        $em = $this->getDoctrine()
            ->getManager();

        $blog = $em->getRepository('BlogerBlogBundle:Blog')->find($blog_id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $blog;
    }

}