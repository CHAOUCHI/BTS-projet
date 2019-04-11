<?php

namespace App\Controller;

use App\Entity\Machine;
use App\Entity\Maintenance;
use App\Entity\Etapes;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\NewMachineFormType;
use App\Form\EtapesType;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\Common\Persistence\ObjectManager;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;




use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 * 
 * @author Mod_loc
 *
 */
class MachineController extends AbstractController
{
    protected $data;
    
    public function setData($data)
    {
        $this->data = $data;
    }
    public function getData()
    {
        return $this->data;
    }
    /**
     * @Route("/machine", name="machine")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
//      Recuperation de toutes les machines existantes ////////////////
        $repository = $em->getRepository(Machine::class);
        $machines = $repository->findAll();
///////////////////////////////////////////////////////////////////////        
        
        
//         $formMachine = $this->createForm(NewMachineFormType::class, $machine);   //Création d'un nouvel objet formulaire agissant sur le nouvel utilisateur créé auparavant
        $formMachine = $this->createFormBuilder(new Machine())     //creation du formulaire
        ->add('name', TextType::class)
        ->add('description', TextareaType::class)
//         ->add('imagefilename', TextType::class,array('attr' => array('maxlength' =>255))) //Pour un maximum de 255 caract�res
        ->add('picturedevant', FileType::class )
        ->add('picturegauche', FileType::class )
        ->add('picturederriere', FileType::class )
        ->add('picturedroite', FileType::class )
        ->add('picturedessus', FileType::class )
        ->add('picturedessous', FileType::class )
        ->add('Enregistrer', SubmitType::class,  array('label' =>'Save Machine'))
        ->getForm();
        $formMachine->handleRequest($request);
        
        if (($formMachine->getClickedButton() && 'Enregistrer' === $formMachine->getClickedButton()->getName())) //BOUTON SAUVEGARDER + APERCU
        {        
            $blog = $formMachine->getData();
            $this->setData($blog);
            $blog->setPicturedevant('1.jpg');
            $blog->setPicturegauche('2.jpg');
            $blog->setPicturederriere('3.jpg');
            $blog->setPicturedroite('4.jpg');
            $blog->setPicturedessus('5.jpg');
            $blog->setPicturedessous('6.jpg');
            $em->persist($blog);        //Pour ajouter � la base de donn�e
            $em->flush();
         
            ///Gestion des images //////////
//             $nommachine = preg_split("/[\s,;:.#'\"\/\{\-\_]+/",$formMachine['picturedevant']->getData());
//             $nomcomplete= "" ;
//             for ($i = 0; $i < count($nommachine); $i++) {
//                 $nomcomplete =  $nomcomplete.$nommachine[$i];
//             }
            
//             $nom = $nomcomplete.$machinegetID->getId().'.jpg';
            $nom = '.jpg';
            $formMachine['picturedevant']->getData()->move(
                ('image/machine/'.$blog->getId().'/'),              //.$document->getId()  => � rajouter si on souhaite ajouter un dossier dans public lors de l'enregistrement de l'image
                '1'.$nom
                );
            $formMachine['picturegauche']->getData()->move(
                ('image/machine/'.$blog->getId().'/'),              //.$document->getId()  => � rajouter si on souhaite ajouter un dossier dans public lors de l'enregistrement de l'image
                '2'.$nom
                );
            $formMachine['picturederriere']->getData()->move(
                ('image/machine/'.$blog->getId().'/'),              //.$document->getId()  => � rajouter si on souhaite ajouter un dossier dans public lors de l'enregistrement de l'image
                '3'.$nom
                );
            $formMachine['picturedroite']->getData()->move(
                ('image/machine/'.$blog->getId().'/'),              //.$document->getId()  => � rajouter si on souhaite ajouter un dossier dans public lors de l'enregistrement de l'image
                '4'.$nom
                );
            $formMachine['picturedessus']->getData()->move(
                ('image/machine/'.$blog->getId().'/'),              //.$document->getId()  => � rajouter si on souhaite ajouter un dossier dans public lors de l'enregistrement de l'image
                '5'.$nom
                );
            $formMachine['picturedessous']->getData()->move(
                ('image/machine/'.$blog->getId().'/'),              //.$document->getId()  => � rajouter si on souhaite ajouter un dossier dans public lors de l'enregistrement de l'image
                '6'.$nom
                );
            
            ////////////////////////////////

            $em->persist($blog);        //Pour ajouter � la base de donn�e
            $em->flush();
            
            
            
            
            $request = 0;
            return $this->redirectToRoute('machine',);
        }
        return $this->render('machine/index.html.twig', [
            'formMachine' => $formMachine->createView(),
            'controller_name' => 'MachineController',
            'Machines' => $machines,
        ]);
    }
    
    /**
     * @Route("/maintenance/{machine}", name="maintenanceModele3D")
     */
    public function viewMaintenances($machine, EntityManagerInterface $em, Request $request)
    {
       $repositoryMaintenance = $em->getRepository(Maintenance::class);
       $repositoryMachine = $em->getRepository(Machine::class);
       $maintenances = $repositoryMaintenance->findBy(['idMachine'=> $repositoryMachine->findOneById(['id'=>$machine])
       ]);
       
       
       
       $machinegetID = $em->getRepository(Machine::class)->findOneBy(['id'=> $machine]);
//        dd($machinegetID->getName());
//         if(!$maintenance)
//         {
//             throw $this->createNotFoundException(sprintf('No maintenance for machine "%s"', $machine));
//         }
//////// CREATION FORMULAIRE D UNE MAINTENANCE     /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $maintenance = new Maintenance();
        $FormMaintenance = $this->createFormBuilder($maintenance)     //creation du formulaire
        ->add('nom', TextType::class)
        ->add('picturefile', FileType::class )
        ->add('Enregistrer', SubmitType::class,  array('label' =>'Sauver la maintenance'))
        ->getForm();
        $FormMaintenance->handleRequest($request);
        
        if (($FormMaintenance->getClickedButton() && 'Enregistrer' === $FormMaintenance->getClickedButton()->getName())) //BOUTON SAUVEGARDER + APERCU
        {
            $createMaintenance = $FormMaintenance->getData();
            $this->setData($createMaintenance);
            $createMaintenance->setIdMachine($machinegetID);
            
            $nommachine = preg_split("/[\s,;:.#'\"\/\{\-\_]+/",$FormMaintenance['nom']->getData());
            $nomcomplete= "" ;
            for ($i = 0; $i < count($nommachine); $i++) {
                $nomcomplete =  $nomcomplete.$nommachine[$i];
            }
            
            $nom = $nomcomplete.$machinegetID->getId().'.jpg';
            

            $createMaintenance->setPicturefile($nomcomplete);
            $createMaintenance->setPicturefilename($nom);
            $em->persist($createMaintenance);        //Pour ajouter � la base de donn�e
            $em->flush();
            $FormMaintenance['picturefile']->getData()->move(
                ('image/machine/'.$machinegetID->getId().'/'.$createMaintenance->getId()),              //.$document->getId()  => � rajouter si on souhaite ajouter un dossier dans public lors de l'enregistrement de l'image
                $nom
                );
            $em->persist($createMaintenance);        //Pour ajouter � la base de donn�e
            $em->flush();
            $request = 0;
            return $this->redirectToRoute('maintenanceModele3D',['machine' => $machine]);
        }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        return $this->render('machine/maintenance.html.twig', [
            'controller_name' => 'MachineController',
            'formMaintenance' => $FormMaintenance->createView(),
            'maintenances' => $maintenances,
            'machineID' => $machinegetID->getId(),
        ]);
    }
    
    /**
     * @Route("/modele/{slug}", name="modele3D")
     */
    public function viewModele($slug, EntityManagerInterface $em, Request $request, ObjectManager $manager)
    {
        $session = new Session();
//         if ($tableau !== '0') 
//         {
//             $arr = array(); // On cr�e l'array qui recevra les donn�es
//             for ($i = 0 ; isset($_GET['arr' . $i]) ; $i++)
//             {
//                 $arr[$i] = $_GET['arr' . $i]; // On y met les donn�es
//             }
          
//             dd($arr);
//         }
        $repositoryMachine = $em->getRepository(Machine::class);
        $repositoryMaintenance = $em->getRepository(Maintenance::class);
        $repositoryEtapes = $em->getRepository(Etapes::class);
        
        $etapes = $repositoryEtapes->findBy(['maintenance' =>  $repositoryMaintenance->findOneBy(['id' =>$slug])->getId()]);
        //$machine = $repositoryMachine->findOneBy(['id'=> $slug]);
        $machine = $repositoryMachine->findOneBy(['id'=>  $repositoryMaintenance->findOneBy(['id' =>$slug])->getIdMachine()
        ]);
        if(!$machine)
        {
            throw $this->createNotFoundException(sprintf('No machine for slug "%s"', $slug));
        }
        
        //////// CREATION DE SPRITE ou ETAPE ///////////////////////////////////
        $sprite = new Etapes();
        //         $formMachine = $this->createForm(NewMachineFormType::class, $machine);   //Création d'un nouvel objet formulaire agissant sur le nouvel utilisateur créé auparavant
      
        
        ////SAVE DUNE SPRITE A LA FOIS     ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//         $formSprite = $this->createFormBuilder($sprite)     //creation du formulaire
//         ->add('name', TextType::class)
//         ->add('description', TextareaType::class)
//         ->add('position', TextType::class)
//         ->add('camera', TextType::class)
//         ->add('etape', TextType::class)
//         ->add('Enregistrer', SubmitType::class,  array('label' =>'Save Etape'))
//         ->getForm();
//         $formSprite->handleRequest($request);
        
//         if (($formSprite->getClickedButton() && 'Enregistrer' === $formSprite->getClickedButton()->getName())) //BOUTON SAUVEGARDER + APERCU
//         {
       
//             /////
//            // dd("rater");
//             $createSprite = $formSprite->getData();
//             $this->setData($createSprite);
// //             $machine = $repositoryMaintenance->findOneBy(['id' =>$slug])
//             $createSprite->setMachine($machine);
//             $createSprite->setMaintenance($repositoryMaintenance->findOneBy(['id' => $slug]));
//             $em->persist($createSprite);        //Pour ajouter � la base de donn�e
//             $em->flush();
//             $request = 0;
// //             window.location.reload()
//             return $this->redirectToRoute('modele3D',['slug'=> $slug]);
//            // return $this->render('/modele/'.$slug.'.html.twig',);
//         }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        ////SAUVEGARDER TOUTES LES SPRITES !!! /////////////////////////////////////
        
        $formSaveAllSprite = $this->createForm(EtapesType::class, $sprite);
        //$this->createFormBuilder($sprite)
//         ->add('name', TextType::class)
//         ->add('description', TextareaType::class)
//         ->add('position', TextType::class)
//         ->add('camera', TextType::class)
//         ->add('etape', TextType::class)
       
// //         ->add('categories', EntityType::class, array(
// //             'class'        => Etapes::class,
// //             'choice_label' => 'name',
// //             'multiple'     => true,
// //         ))
//         ->add('val', SubmitType::class, array('label' =>'testdetoutsave'))
//         ->add('Sauvegarder', SubmitType::class,  array('label' =>'Sauver la maintenance'))
//         ->getForm();
        $formSaveAllSprite->handleRequest($request);
        $saveRequest = $request;
      
      
        if($formSaveAllSprite->isSubmitted() && 'Sauvegarder' === $formSaveAllSprite->getClickedButton()->getName() )
        {
  
            $sprite = $formSaveAllSprite->getData();
            $this->setData($sprite);
//             $sprite->setName($k);
            
            $nameFromSprites = json_decode($sprite->getName());
            $descriptionFromSprites = json_decode($sprite->getDescription());
            $posCameraFromSprites = json_decode($sprite->getCamera());
            $OrdreFromSprites = json_decode($sprite->getEtape());
            $getOdreEtapeAndLengthTableau = json_decode($sprite->getPosition());
            
            
            $sprite->setName($nameFromSprites[0]->object->name);
            $sprite->setDescription($descriptionFromSprites[0]);
            $sprite->setPosition($nameFromSprites[0]->object->matrix[12].';'.$nameFromSprites[0]->object->matrix[13].';'.$nameFromSprites[0]->object->matrix[14]);
            $sprite->setCamera($posCameraFromSprites[0]);
            $sprite->setEtape($getOdreEtapeAndLengthTableau[0]);
            //R�cup�rer le nom
            //  $nameFromSprites[0]->object->name
            
            // R�cup�rer la position X Y Z :
//             $positionFromSprites[0]->object->matrix[12];
//             $positionFromSprites[0]->object->matrix[13];
//             $positionFromSprites[0]->object->matrix[14];

            //R�cup�rer la position de la cam�ra
           //   $posCameraFromSprites[0]
                
            //r�cup�rer la description
            //  $descriptionFromSprites[0]
            
            //r�cup�rer l'odre de l'�tape
                
            
      //      dd(count($getOdreEtapeAndLengthTableau));
            
            
//             dd(json_decode($sprite->getName()));
            $sprite->setMachine($machine);
            $sprite->setMaintenance($repositoryMaintenance->findOneBy(['id' => $slug]));
          //  dd($sprite);
            //                 dd($sprite);
            $em->persist($sprite);
          
            for($k=1; $k<= (count($getOdreEtapeAndLengthTableau)-1); $k++)
            {
                
                $createSprite2 = clone $sprite;
//                 $createSprite2 = $formSaveAllSprite->getData();
//                 $this->setData($createSprite2);
                $createSprite2->setName($nameFromSprites[$k]->object->name);
                $createSprite2->setDescription($descriptionFromSprites[$k]);
                $createSprite2->setPosition($nameFromSprites[$k]->object->matrix[12].';'.$nameFromSprites[$k]->object->matrix[13].';'.$nameFromSprites[$k]->object->matrix[14]);
                $createSprite2->setCamera($posCameraFromSprites[$k]);
                $createSprite2->setEtape($getOdreEtapeAndLengthTableau[$k]);
                
//                 $createSprite2->setMachine($machine);
//                 $createSprite2->setMaintenance($repositoryMaintenance->findOneBy(['id' => $slug]));
//                 dd($createSprite2);
                $em->persist($createSprite2);
          
          
          //                 $em->persist($createSprite2);        //Pour ajouter � la base de donn�e
//                 $em->flush();
              //  $em->clear();
               
              
            }
            $em->flush();
          
               // $request = 0;
            //             dd($createSprite);
//             if('val' === $formSaveAllSprite->getClickedButton()->getName())
//             {
          
                return $this->redirectToRoute('modele3D',['slug'=> $slug]);
//             }
            
            //             dd($formSaveAllSprite->getData());
//             $spriteGoDelete = $repositoryEtapes->findBy(['id'=> $formSaveAllSprite->getData()['idSprite'] ]);
            
//                         foreach($spriteGoDelete as $spritedelete)
//                         {
//                             $em->remove($spritedelete);
//                             $em->flush();
//                             return $this->redirectToRoute('modele3D',['slug'=> $slug]);
//                         }
            
        }
        ////////////////////////////////////////////////////////////////////////////
        
        
        
        ////////////DELETE SPRITE ////////////////////////////////////////////
        
        $formDeleteSprite = $this->createFormBuilder()
        ->add('idSprite', TextType::class)
        ->add('Suppression', SubmitType::class,  array('label' =>'Supprimer une etape'))
        ->getForm();
        $formDeleteSprite->handleRequest($request);
        
        if($formDeleteSprite->isSubmitted() && 'Suppression' === $formDeleteSprite->getClickedButton()->getName())
        {
            // dd($formDeleteSprite->getData());
           
            $spriteGoDelete = $repositoryEtapes->findBy(['id'=> $formDeleteSprite->getData()['idSprite'] ]);
            foreach($spriteGoDelete as $spritedelete)
            {
                $em->remove($spritedelete);
                $em->flush();
                return $this->redirectToRoute('modele3D',['slug'=> $slug]);
            }

        }
   
     ///////////////////////////////////////////////////////////////////////////////
//         $tab_js = $_POST['un_id'];
        
//         $tab_php = explode("<br>", $tab_js);
        
//         echo $tab_php[0];
        
        return $this->render('machine/viewmodel.html.twig', [
            'controller_name' => 'MachineController',
         //   'formEtape' => $formSprite->createView(),
            'formDelete'=> $formDeleteSprite->createView(),
            'machine' => $machine,
            'etapes' => $etapes,
            'saveAllSprites' =>$formSaveAllSprite->createView(),
            
        ]);
        
        
    }
}
