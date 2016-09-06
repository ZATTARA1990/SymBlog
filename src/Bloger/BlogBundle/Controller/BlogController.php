<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 5.9.16
 * Time: 12.24
 */

namespace Bloger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Blog controller.
 */
class BlogController extends Controller
{
    /**
     * Show a blog entry
     */
       public function showAction($id, $slug, $comments)
    {
           $em = $this->getDoctrine()->getManager();

        $blog = $em->getRepository('BlogerBlogBundle:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post O_o.');
        }

        $comments = $em->getRepository('BlogerBlogBundle:Comment')
            ->getCommentsForBlog($blog->getId());

        return $this->render('BlogerBlogBundle:Blog:show.html.twig', array(
            'blog'      => $blog,
            'comments'  => $comments));
    }
}