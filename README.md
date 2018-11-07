# doc-img-bundle

Insert into config.yml
~~~~
  aldaflux_doc_img:
      web_dir: "%kernel.root_dir%/../web"
 ~~~~
in tour controller : 
~~~~
  $view_html = $this->renderView('AldafluxDocImgBundle:Default:example.html.twig');
  $view_mht=$this->get('HtmlToMht')->GenereHtmlToMht($view_html);
~~~~  
you can write the view in a file (".doc") or send it in response

~~~~
        $response = new Response();
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', "application/vnd.ms-word");
        $response->headers->set('Content-Disposition', 'attachment; filename="file.doc"');
        $response->sendHeaders();
        $response->setContent($view_mht);
        return ($response);

~~~~

