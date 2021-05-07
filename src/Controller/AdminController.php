<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Entity\Formation;
use App\Entity\Utilisateur;
use App\Form\FormationType;
use App\Form\UtilisateurType;
use App\Repository\FormationRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(FormationRepository $repFormation): Response
    {
        return $this->render('admin/index.html.twig', [
            'listeFormations' => $repFormation->findAll(),
        ]);
    }

    /**
     * @Route("/admin/inscription", name="inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $utilisateur = new Utilisateur;
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->add("submit", SubmitType::class, array('label' => "S'inscrire", 'attr' => ['class' => "mt-3 btn btn-primary"]));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $password = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($password);
            $em->persist($utilisateur);
            $em->flush();

            return $this->redirectToRoute("admin",[]);
        }

        return $this->render('admin/inscription.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/addformation", name="addFormation")
     */
    public function creerFormation(Request $request): Response
    {
        $formation = new Formation;
        $form = $this->createForm(FormationType::class, $formation);
        $form->add("submit", SubmitType::class, array('label' => 'Créer'));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {    
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();

            return $this->redirectToRoute("admin",[]);
        }

        return $this->render('admin/creerFormation.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/modformation/{id}", name="modFormation")
     */
    public function modifierFormation(Request $request, Formation $formation): Response
    {

        $form = $this->createForm(FormationType::class, $formation);
        $form->add("submit", SubmitType::class, array('label' => 'Modifier'));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();
            return $this->redirectToRoute("admin",[]);
        }

        return $this->render('admin/creerFormation.html.twig', [
            "form" => $form->createView()
        ]);

    }

    /**
     * @Route("/admin/supformation/{id}", name="supFormation")
     */
    public function supprimerProduitAction(Formation $formation): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($formation);
        $em->flush();

        return $this->redirectToRoute("admin");
    }

     /**
     * @Route("/admin/addmodule", name="addModule")
     */
    public function creerModule(Request $request): Response
    {
        $module = new Module;
        $form = $this->createForm(ModuleType::class, $module);
        $form->add("submit", SubmitType::class, array('label' => 'Créer'));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {    
            $em = $this->getDoctrine()->getManager();
            $em->persist($module);
            $em->flush();

            return $this->redirectToRoute("admin",[]);
        }

        return $this->render('admin/creerModule.html.twig', [
            "form" => $form->createView()
        ]);
    }

     /**
     * @Route("/admin/modmodule/{id}", name="modModule")
     */
    public function modifierModule(Request $request, Module $module): Response
    {

        $form = $this->createForm(ModuleType::class, $module);
        $form->add("submit", SubmitType::class, array('label' => 'Modifier'));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($module);
            $em->flush();
            return $this->redirectToRoute("admin",[]);
        }

        return $this->render('admin/creerModule.html.twig', [
            "form" => $form->createView()
        ]);

    }

    /**
     * @Route("/admin/supmodule/{id}", name="supModule")
     */
    public function supprimerModule(Module $module): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($module);
        $em->flush();

        return $this->redirectToRoute("admin");
    }
}
