<?php

/**
 * Created by PhpStorm.
 * User: khouloud
 * Date: 01/08/16
 * Time: 01:13 م
 */
namespace Test\FrontBundle\Form\Handler;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class SheetHandler
{
    /**
     * @var Form
     */
    protected $form;
    /**
     * @var Request
     */
    protected $request;
    //Gestion du formulaire
    /**
     * SheetHandler constructor.
     * @param Form $form
     * @param Request $request
     */
    public function  __construct(Form $form,Request $request)
    {
        //pour initialiser notre formulaire
        $this->form=$form;
        $this->request=$request;
    }

    /**
     * @return bool*
     */
    public function process()
    {
        //pour gérer le formulaire
       $this->form->handleRequest($this->request);
        if($this->request->isMethod('post') && $this->form->isValid())
        {
            return true;
        }
            return false ;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    public function onSucces()
    {
        
    }




}