<?php

namespace App\Controller;

use App\Entity\VideoGame;
use App\Form\Type\TaskType;
use App\Form\VideoGameType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VideoGameController extends AbstractController
{
    /**
     * @Route("/video_game", name="video-game")
     */
    public function index()
    {
        return $this->render('video_game/index.html.twig', [
            'controller_name' => 'VideoGameController',
        ]);
    }

    /**
     * @Route("/creation_VG", name="creation-VG") 
     * @IsGranted("ROLE_ADMIN")
     */ 
    public function createEditorAction(Request $request)
    {   
        $VG = new VideoGame();   
        $form = $this->createForm(VideoGameType::class, $VG);   
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {  
            $entityManager = $this->getDoctrine()->getManager();       
            $entityManager->persist($VG);       
            $entityManager->flush();
            return $this->redirectToRoute('liste-VG');   
        }
    return $this->render('video_game/creat.html.twig', [       
        'VGForm' => $form->createView(),   
        ]); 
    }

    /**
     * @Route("/liste_VG", name="liste-VG")
     * @IsGranted("ROLE_USER")
     */
    public function listAction()
    {
        $list = $this->getDoctrine()
                    ->getRepository(VideoGame::class)
                    ->findAll();

        return $this->render('video_game/show.html.twig', [
            'list' => $list,
        ]);
    }

    /**
     * @Route("/one_VG", name="one-VG")
     * @IsGranted("ROLE_USER")
     */
    public function listOneAction()
    {
        $id = $_GET['id'];
        $VG = $this->getDoctrine()
                    ->getRepository(VideoGame::class)
                    ->find($id);

        return $this->render('video_game/showOne.html.twig', [
            'VG' => $VG,
        ]);
    }

    /**
     * @Route("/modife_VG", name="modife-VG")
     * @IsGranted("ROLE_ADMIN")
     */
    public function modifeVGAction(Request $request)
    { 
        $id = $_GET['id'];
        $VG = $this->getDoctrine()
                    ->getRepository(VideoGame::class)
                    ->find($id);

        $form = $this->createForm(VideoGameType::class, $VG);   
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {  
            $entityManager = $this->getDoctrine()->getManager();       
            $entityManager->persist($VG);       
            $entityManager->flush();
            return $this->redirectToRoute('liste-VG');   
        }
    return $this->render('video_game/creat.html.twig', [       
        'VGForm' => $form->createView(),   
        ]); 
    }

    /**
     * @Route("/supr_VG", name="supr-VG")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deletAction()
    {
        $id = $_GET['id'];
        $VG = $this->getDoctrine()
                    ->getRepository(VideoGame::class)
                    ->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($VG);
        $em->flush();

        return $this->redirectToRoute('liste-VG');
    }
}
