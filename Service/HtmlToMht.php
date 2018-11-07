<?php
 namespace Aldaflux\DocImgBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
 
class HtmlToMht  
{
 
        public function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }
          public function GenereHtmlToMht($file_content)
        {
            $file_content_replaced=$this->mht_replace_images($file_content); 
            global $images_64;
            $reponse=$this->entete_mht();
            $reponse.=base64_encode($file_content_replaced);
            $reponse.=$images_64;
            $reponse.="\n";
            $reponse.="\n";
            $reponse.="------=_NextPart_ZROIIZO.ZCZYUACXV.ZARTUI--";
            return($reponse);
        }


        private function entete_mht()
        {
            $reponse= "MIME-Version: 1.0\n";
            $reponse.= 'Content-Type: multipart/related; boundary="----=_NextPart_ZROIIZO.ZCZYUACXV.ZARTUI"';
            $reponse.= "\n";
            $reponse.= "\n";

                $reponse.= "------=_NextPart_ZROIIZO.ZCZYUACXV.ZARTUI\n";
                $reponse.= "Content-Location: file:///C:/mydocument.htm\n";
                $reponse.= "Content-Transfer-Encoding: base64\n";
                $reponse.='Content-Type: text/html; charset="utf-8"';
                $reponse.= "\n";
                $reponse.= "\n";
                $reponse.= "\n";
            return($reponse);
        }

 

        private function mht_get_image($file_path)
        {
            $ext = pathinfo($file_path, PATHINFO_EXTENSION);
            $file_name=$this->get_name_unique($file_path);

            $reponse= "\n";
            $reponse.="------=_NextPart_ZROIIZO.ZCZYUACXV.ZARTUI\n";
            $reponse.="Content-Location: file:///C:/mydocument_files/".$file_name."\n";
            $reponse.="Content-Transfer-Encoding: base64\n";
            $reponse.="Content-Type: image/".$ext."\n";
            $reponse.="\n";
 
            $web_dir=$this->container->getParameter('aldaflux_doc_img.web_dir');
            
            if (filter_var($file_path, FILTER_VALIDATE_URL))
            {
                $reponse.=base64_encode( file_get_contents($file_path));
            }
            else
            {
                $reponse.=base64_encode( file_get_contents($web_dir.$file_path));
            }
            $reponse.="\n";
            $reponse.="\n";
            return($reponse);
        }


        private function get_name_unique($file_name)
        {
            $file_name=str_replace("://", "_", $file_name);
            $file_name=str_replace("/", "_", $file_name);
            return($file_name);
        }



        private function mht_replace_images($file_content)
        {
 
            global $images_64;
            preg_match_all('#src="(.*?)"#i', $file_content,$images);
            $images_file_name=array_unique($images[1]);
            foreach ($images_file_name as $image)
            {
            

                $images_64.=$this->mht_get_image($image);
                $old='src="'.$image.'"';
                $new='src="mydocument_files/'.$this->get_name_unique($image).'"';
                $file_content=str_replace($old, $new, $file_content);

            }
            return($file_content);
        }



}
    