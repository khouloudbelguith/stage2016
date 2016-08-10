<?php
/**
 * Created by PhpStorm.
 * User: khouloud
 * Date: 27/07/16
 * Time: 12:01 م
 */

namespace Test\FrontBundle\Controller;


use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\EntityNotFoundException;
use DoctrineTest\InstantiatorTestAsset\ExceptionAsset;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Test\FrontBundle\Entity\Sheet;
use Test\FrontBundle\Entity\Repository;
use Test\FrontBundle\Form\Handler\SheetHandler;
use Test\FrontBundle\Form\Type\SheetType;

class SheetController extends Controller
{
    public function listAction(Request $request)
    {

     /*  // return $this->forward('TestFrontBundle:Sheet:sheet',array('id'=> 1));
        //matbaddalch l'entéte
       // $url =$this->generateUrl('test_front_sheet',array('id'=>1));
      //return $this->redirect($url);
      //tbaddel il entéte
      //var_dump($url);die;*/

        $em =$this->getDoctrine()->getManager();
        $repository=$this->getDoctrine()->getManager()->getRepository('TestFrontBundle:Sheet');
        $sheets=$repository->getAll();
//        var_dump($sheets);die;
//        $sheet =new Sheet();
//        $sheet->setName('MTV');
//        $sheet->setType('audio');
//        $sheet->setArtist('adele');
//        $sheet->setDuration('04:05');
//        $sheet->setReleased(new  \DateTime(null));
//        $em->persist($sheet);
//        $em->flush();
        $session = $this->get('session');
        $session->set('greeting','good morning');
        $session->getFlashBag()->add('greeting', 'hello');
        // var_dump($session->get('greeting'));die;
        return $this->render('TestFrontBundle:Sheet:list.html.twig',array('sheets'=> $sheets));
    }
    public function showAction($id,Request $request)
    {
        //var_dump($request->isMethod('get'));die;
        //var_dump($id);die;
        $repository= $this->getDoctrine()->getManager()->getRepository('TestFrontBundle:Sheet');
        $sheet = $repository->find($id);
        if (!$sheet)
        {
            throw new EntityNotFoundException();
        }
        return $this->render('TestFrontBundle:Sheet:show.html.twig',array('sheet' => $sheet));
    }
    public function createAction(Request $request)
    {
                       /* $form=$this->createForm(new SheetType(),new Sheet());
            
                     * $form=$this->createFormBuilder(new Sheet())//un passage d'identité
                        ->add('name',null,array('label'=>'Nom de l\'album '))
                        ->add('type',null,array('label'=>'Type de l\'album'))
                        ->add('artist',null,array('label'=>'Artist'))
                        ->add('duration',null,array('label'=>'Duration de l\'album'))
                        ->add('released','date',array('label'=>'Date de l\'album'))
                        ->add('submit','submit')// les add pour la personalisation
                        ->getForm();//pour récupérer le formulaire
                    c'est la logique :il faut le déplacer
            
                    $form->handleRequest($request);
                    if($request->isMethod('post') && $form->isValid())
                    {
                        $em =$this->getDoctrine()->getManager();
                        $em->persist($form->getData());
                        $em->flush();*/
       // $formHandler= new SheetHandler($this->createForm(new SheetType(),new Sheet()),$request);
        $formHandler=$this->get('sheet_handler');
        if($formHandler->process())
        {
            $em =$this->getDoctrine()->getManager();
            $em->persist($formHandler->getForm()->getData());
            $em->flush();

          return  $this->redirect($this->generateUrl('test_front_sheet_list'));
        }
        return $this->render('TestFrontBundle:Sheet:create.html.twig',array('form'=> $formHandler->getForm()->createView()));

    }

}