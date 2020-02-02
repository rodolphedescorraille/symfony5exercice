<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\Type\TaskType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    /**
     * @Route("/creation_utilisateur", name="creation-utilisateur") 
     */ 
    public function createUserAction(Request $request)
    {   
        $user = new User();   
        $form = $this->createForm(UserType::class, $user);   
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {   
            $user->setDateCreat(new \DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();       
            $entityManager->persist($user);       
            $entityManager->flush();
            return $this->redirectToRoute('liste-utilisateur');   
        }
    return $this->render('user/creat.html.twig', [       
        'userForm' => $form->createView(),   
        ]); 
    }

    /**
     * @Route("/liste_utilisateur", name="liste-utilisateur")
     * @IsGranted("ROLE_ADMIN")
     */
    public function listAction()
    {
        $list = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->findAll();

        return $this->render('user/show.html.twig', [
            'list' => $list,
        ]);
    }

     /**
     * @Route("/one_utilisateur", name="one-utilisateur")
     * @IsGranted("ROLE_USER")
     */
    public function listOneAction()
    {
        $id = $_GET['id'];
        $editor = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->find($id);

        return $this->render('user/showOne.html.twig', [
            'user' => $editor,
        ]);
    }

    /**
     * @Route("/modife_utilisateur", name="modife-utilisateur")
     * @IsGranted("ROLE_USER")
     */
    public function modifeUserAction(Request $request)
    { 
        $id = $_GET['id'];
        $editor = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->find($id);

        $form = $this->createForm(UserType::class, $editor);   
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {  
            $entityManager = $this->getDoctrine()->getManager();       
            $entityManager->persist($editor);       
            $entityManager->flush();
            return $this->redirectToRoute('liste-utilisateur');   
        }
    return $this->render('editor/creat.html.twig', [       
        'editorForm' => $form->createView(),   
        ]); 
    }

    /**
     * @Route("/supr_utilisateur", name="supr-utilisateur")
     * @IsGranted("ROLE_USER")
     */
    public function deletAction()
    {
        $id = $_GET['id'];
        $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->find($id);
                    
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('liste-utilisateur');
    }
}
