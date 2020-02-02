<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Form\EditorType;
use App\Form\Type\TaskType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditorController extends AbstractController
{

    /**
     * @Route("/creation_editeur", name="creation-editeur") 
     * @IsGranted("ROLE_ADMIN")
     */ 
    public function createEditorAction(Request $request)
    {   
        $editor = new Editor();   
        $form = $this->createForm(EditorType::class, $editor);   
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {  
            $entityManager = $this->getDoctrine()->getManager();       
            $entityManager->persist($editor);       
            $entityManager->flush();
            return $this->redirectToRoute('liste-editeur');   
        }
    return $this->render('editor/creat.html.twig', [       
        'editorForm' => $form->createView(),   
        ]); 
    }

    /**
     * @Route("/liste_editeur", name="liste-editeur")
     * @IsGranted("ROLE_ADMIN")
     */
    public function listAction()
    {
        $list = $this->getDoctrine()
                    ->getRepository(Editor::class)
                    ->findAll();

        return $this->render('editor/show.html.twig', [
            'list' => $list,
        ]);
    }

     /**
     * @Route("/one_editeur", name="one-editeur")
     * @IsGranted("ROLE_ADMIN")
     */
    public function listOneAction()
    {
        $id = $_GET['id'];
        $editor = $this->getDoctrine()
                    ->getRepository(Editor::class)
                    ->find($id);

        return $this->render('editor/showOne.html.twig', [
            'editor' => $editor,
        ]);
    }

    /**
     * @Route("/modife_editeur", name="modife-editeur")
     * @IsGranted("ROLE_ADMIN")
     */
    public function modifeEditorAction(Request $request)
    { 
        $id = $_GET['id'];
        $editor = $this->getDoctrine()
                    ->getRepository(Editor::class)
                    ->find($id);

        $form = $this->createForm(EditorType::class, $editor);   
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {  
            $entityManager = $this->getDoctrine()->getManager();       
            $entityManager->persist($editor);       
            $entityManager->flush();
            return $this->redirectToRoute('liste-editeur');   
        }
    return $this->render('editor/creat.html.twig', [       
        'editorForm' => $form->createView(),   
        ]); 
    }

    /**
     * @Route("/supr_editeur", name="supr-editeur")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deletAction()
    {
        $id = $_GET['id'];
        $editor = $this->getDoctrine()
                    ->getRepository(Editor::class)
                    ->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($editor);
        $em->flush();

        return $this->redirectToRoute('liste-editeur');
    }
}
