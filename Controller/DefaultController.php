<?php

namespace Aldaflux\DocImgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/aldaflux")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        error_log("docAction OK");
        return $this->render('AldafluxDocImgBundle:Default:index.html.twig');
    }
    
    
    /**
     * Genre a word.
     *
     * @Route("/word", name="aldaflux_doc_img_doc") 
     */
    public function docAction()
    {
         
        $view_html = $this->renderView('AldafluxDocImgBundle:Default:example.html.twig');
        $view_mht=$this->get('HtmlToMht')->GenereHtmlToMht($view_html);
        
        $response = new Response();
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', "application/vnd.ms-word");
        $response->headers->set('Content-Disposition', 'attachment; filename="file.doc"');
        $response->sendHeaders();
        $response->setContent($view_mht);
        return ($response);
    }
    
    
    
    /**
     * Genre a word.
     *
     * @Route("/preview", name="aldaflux_doc_img_preview") 
     */
    public function previewAction()
    {
        return $this->render('AldafluxDocImgBundle:Default:example.html.twig');
    }
    
    
}
