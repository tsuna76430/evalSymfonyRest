<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\ModuleRepository;
use App\Repository\SeanceRepository;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(AuthorizationCheckerInterface $authorizationChecker, FormationRepository $repFormation, ModuleRepository $repModule): Response
    {
        if ($authorizationChecker->isGranted("ROLE_ADMIN"))
        {
            return $this->render('admin/index.html.twig', [
                'listeFormations' => $repFormation->findAll(),
            ]);
        }
        else if($authorizationChecker->isGranted("ROLE_FORMATEUR"))
        {
            $listeFormations = $repFormation->findAll();
            return $this->render('accueil/index.html.twig', [
                "listeFormations" => $listeFormations
            ]);
        }
        else
        {
            $formation = $repFormation->findAll();
            $listeModules = $repModule->findAll();
            return $this->render('accueil/index.html.twig', [
                "listeFormations" => $formation,
                "listeModules" => $listeModules

            ]);
        }
    }

    /**
     * @Route("/formation/{nom}", name="formation")
     */
    public function formation(AuthorizationCheckerInterface $authorizationChecker, FormationRepository $repFormation, ModuleRepository $repModule, $nom): Response
    {
        if ($authorizationChecker->isGranted("ROLE_ADMIN"))
        {
            $formation = $repFormation->findOneBy(array("nom" => $nom));
            $listeModules = $repModule->findAll();
            return $this->render('accueil/formation.html.twig', [
                "formation" => $formation,
                "listeModules" => $listeModules

            ]);
        }
        else if($authorizationChecker->isGranted("ROLE_FORMATEUR"))
        {
            $formation = $repFormation->findOneBy(array("nom" => $nom));
            $listeModules = $repModule->findAll();
            return $this->render('accueil/formation.html.twig', [
                "formation" => $formation,
                "listeModules" => $listeModules

            ]);
        }
        else
        {
            return $this->render('accueil/index.html.twig');
        }
    }

    /**
     * @Route("/formation/{nomFormation}/{nomModule}", name="module")
     */
    public function module(Request $request, SluggerInterface $slugger, AuthorizationCheckerInterface $authorizationChecker, SeanceRepository $repSeance, FormationRepository $repFormation, ModuleRepository $repModule): Response
    {
        if($authorizationChecker->isGranted("ROLE_ADMIN"))
        {
            $listeSeance = $repSeance->findAll();
            return $this->render('accueil/seance.html.twig', [
                "listeFormations" => $repFormation->findAll(),
                "listeModules" => $repModule->findAll(),
                "listeSeances" => $listeSeance,
            ]);
        }
        else if($authorizationChecker->isGranted("ROLE_FORMATEUR"))
        {
            $seance = new Seance;
            $form = $this->createForm(SeanceType::class, $seance);
            $form->add("submit", SubmitType::class, array('label' => "Ajouter", 'attr' => ['class' => "mt-3 btn btn-primary"]));

            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $document = $form->get('fichier')->getData();

                if ($document) {
                    $originalFilename = pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$document->guessExtension();
    
                    try {
                        $document->move(
                            $this->getParameter('document_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    $seance->setFichier($newFilename);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($seance);
                $em->flush();

                return $this->redirectToRoute("accueil",[]);
            }

            return $this->render('accueil/seance.html.twig', [
                "form" => $form->createView(),
            ]);
        }
        else
        {
            $listeSeance = $repSeance->findAll();
            return $this->render('accueil/seance.html.twig', [
                "listeFormations" => $repFormation->findAll(),
                "listeModules" => $repModule->findAll(),
                "listeSeances" => $listeSeance,
            ]);
        }
    }
}

