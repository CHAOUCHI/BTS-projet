<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use App\Repository\EventRepository;
use App\Entity\Machine;
use App\Repository\MachineRepository;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Maintenance;
use App\Repository\MaintenanceRepository;
class AjaxReceiveController extends AbstractController
{
    /**
     * @Route("accesbdd/sendevent/{parse}", name="sendevent") 
     */
    public function SendEvent(EventRepository $repo,$parse){
        if($parse && $parse != "undefined"){    //Si aucun id n'est passer en param on fait un SELECT * FROM event; sinon SELECT * FROM event WHERE id = $parse;
           $table = $repo->findOneBy([
                'id'=>$parse
            ]);
            if($table === null){
                $message = json_encode("empty");
                echo ($message);
                return $this->render('ajax_receive/index.html.twig'); 
            }
            $tabl = array();
            $j = 0;
            $tabl[$j] = $table->getId();
            $tabl[++$j] = $table->getTitle();
            $tabl[++$j] = $table->getDescription();
            $tabl[++$j] = $table->getUsersid()[0];
            $tabl[++$j] = $table->getMachinesid()[0];
            $tabl[++$j] = $table->getDateStart();
            $tabl[++$j] = $table->getDateEnd();
            $tabl[++$j] = $table->getFrequence();
            
            $message = json_encode($tabl);
            echo ($message);
        
            return $this->render('ajax_receive/index.html.twig'); 
        }
        $table = $repo->findAll();
        if($table === null){
            $message = json_encode("empty");
            echo ($message);
            return $this->render('ajax_receive/index.html.twig'); 
        }
        $tabl = array();
        
        for ($i=0; $i < sizeof($table); $i++){
            $j = 0;
            $tabl[$i][$j] = $table[$i]->getId();
            $tabl[$i][++$j] = $table[$i]->getTitle();
            $tabl[$i][++$j] = $table[$i]->getDescription();
            $tabl[$i][++$j] = $table[$i]->getUsersid()[0];
            $tabl[$i][++$j] = $table[$i]->getMachinesid()[0];
            $tabl[$i][++$j] = $table[$i]->getDateStart();
            $tabl[$i][++$j] = $table[$i]->getDateEnd();
            $tabl[$i][++$j] = $table[$i]->getFrequence();
        }
        $message = json_encode($tabl);
        echo ($message);
        
        return $this->render('ajax_receive/index.html.twig'); 
    }
     /**
     * @Route("accesbdd/sendmachine/{parse}", name="sendmachine") 
     */
    public function SendMachine(MachineRepository $repo,$parse){
        if($parse && $parse != "undefined"){    //Si aucun id n'est passer en param on fait un SELECT * FROM machine; sinon SELECT * FROM machine WHERE id = $parse;
           $table = $repo->findOneBy([
                'id'=>$parse
            ]);
            if($table === null){
                $message = json_encode("empty");
                echo ($message);
                return $this->render('ajax_receive/index.html.twig'); 
            }
            $tabl = array();
            
            $j = 0; 
            $tabl[$j] = $table->getId();
            $tabl[++$j] = $table->getName();
            $tabl[++$j] = $table->getDescription();
            $tabl[++$j] = $table->getImagefilename();
            $tabl[++$j] = $table->getPicturedevant();
            $tabl[++$j] = $table->getPicturegauche();
            $tabl[++$j] = $table->getPicturederriere();
            $tabl[++$j] = $table->getPicturedroite();
            $tabl[++$j] = $table->getPicturedessus();
            $tabl[++$j] = $table->getPicturedessous();
            
            $message = json_encode($tabl);
            echo ($message);
        
            return $this->render('ajax_receive/index.html.twig'); 
        }
        $table = $repo->findAll();
        if($table === null){
            $message = json_encode("empty");
            echo ($message);
            return $this->render('ajax_receive/index.html.twig'); 
        }
        $tabl = array();
        
        for ($i=0; $i < sizeof($table); $i++){
            $j=0;
            $tabl[$i][$j] = $table[$i]->getId();
            $tabl[$i][++$j] = $table[$i]->getName();
            $tabl[$i][++$j] = $table[$i]->getDescription();
            $tabl[$i][++$j] = $table[$i]->getImagefilename();
            $tabl[$i][++$j] = $table[$i]->getPicturedevant();
            $tabl[$i][++$j] = $table[$i]->getPicturegauche();
            $tabl[$i][++$j] = $table[$i]->getPicturederriere();
            $tabl[$i][++$j] = $table[$i]->getPicturedroite();
            $tabl[$i][++$j] = $table[$i]->getPicturedessus();
            $tabl[$i][++$j] = $table[$i]->getPicturedessous();
        }
        
        $message = json_encode($tabl);
        echo ($message);
        
        return $this->render('ajax_receive/index.html.twig'); 
    }
    /**
     * @Route("accesbdd/sendmaintenance/{parse}", name="sendmaintenance") 
     */
    public function SendMaintenance(MaintenanceRepository $repo,$parse){
        if($parse && $parse != "undefined"){    //Si aucun id n'est passer en param on fait un SELECT * FROM machine; sinon SELECT * FROM machine WHERE id = $parse;
           $table = $repo->findOneBy([
                'id'=>$parse
            ]);
            if($table === null){
                $message = json_encode("empty");
                echo ($message);
                return $this->render('ajax_receive/index.html.twig'); 
            }
            $tabl = array();
            
            $j = 0; 
            $tabl[$j] = $table->getId();
            $tabl[++$j] = $table->getIdMachine()->getId();
            $tabl[++$j] = $table->getNom();
            $tabl[++$j] = $table->getPicturefile();
            $tabl[++$j] = $table->getPicturefilename();
            
            $message = json_encode($tabl);
            echo ($message);
        
            return $this->render('ajax_receive/index.html.twig'); 
        }
        $table = $repo->findAll();
        if($table === null){
            $message = json_encode("empty");
            echo ($message);
            return $this->render('ajax_receive/index.html.twig'); 
        }
        $tabl = array();
        
        for ($i=0; $i < sizeof($table); $i++){
            $j=0;
            $tabl[$i][$j] = $table[$i]->getId();
            $tabl[$i][++$j] = $table[$i]->getIdMachine()->getId();
            $tabl[$i][++$j] = $table[$i]->getNom();
            $tabl[$i][++$j] = $table[$i]->getPicturefile();
            $tabl[$i][++$j] = $table[$i]->getPicturefilename();
        }
        
        $message = json_encode($tabl);
        echo ($message);
        
        return $this->render('ajax_receive/index.html.twig'); 
    }
    /**
     * @Route("accesbdd/senduser/{parse}", name="senduser") 
     */
    public function SendUser(UserRepository $repo,$parse){
        if($parse && $parse != "undefined"){        //Si aucun id n'est passer en param on fait un SELECT * FROM user; sinon SELECT * FROM user WHERE id = $parse
            $table = $repo->findOneBy([
                'id'=>$parse
            ]);
            if($table === null){
                $message = json_encode("empty");
                echo ($message);
                return $this->render('ajax_receive/index.html.twig'); 
            }
            $tabl = array();
            
            $j = 0; 
            $tabl[$j] = $table->getId();
            $tabl[++$j] = $table->getEmail();
            //$tabl[++$j] = $table->getUsername();
            $tabl[++$j] = $table->getRoles();
            $tabl[++$j] = $table->getNom();
            $tabl[++$j] = $table->getPrenom();
            $tabl[++$j] = $table->getDatecreation();
            $message = json_encode($tabl);
            echo ($message);
            return $this->render('ajax_receive/index.html.twig');
        }
        $table = $repo->findAll();
        if($table === null){
            $message = json_encode("empty");
            echo ($message);
            return $this->render('ajax_receive/index.html.twig'); 
        }
        $tabl = array();
        for ($i=0; $i < sizeof($table); $i++){
            $j=0; 
            $tabl[$i][$j] = $table[$i]->getId();
            $tabl[$i][++$j] = $table[$i]->getEmail();
            //$tabl[$i][++$j] = $table[$i]->getUsername();
            $tabl[$i][++$j] = $table[$i]->getRoles();
            $tabl[$i][++$j] = $table[$i]->getNom();
            $tabl[$i][++$j] = $table[$i]->getPrenom();
            $tabl[$i][++$j] = $table[$i]->getDatecreation();
        }
        $message = json_encode($tabl);
        echo ($message);
        return $this->render('ajax_receive/index.html.twig');
    }
    /**
     * @Route("variables/sendsession", name="sendsession") 
     */
    public function SendSession(SessionInterface $session,UserRepository $repo){
        $user = $repo->findby(
            [ 
                'email' => $session->all()["_security.last_username"]
            ]
        );
        $tabl = array();
        $j=0;
        $tabl[$j] = $user[0]->getId();
        $tabl[++$j] = $user[0]->getEmail();
        //$tabl[++$j] = $user[0]->getUsername();
        $tabl[++$j] = $user[0]->getRoles();
        $tabl[++$j] = $user[0]->getNom();
        $tabl[++$j] = $user[0]->getPrenom();
        $tabl[++$j] = $user[0]->getDatecreation();
        
        $message = json_encode($tabl);
        
        echo ($message);
        return $this->render('ajax_receive/index.html.twig');
    }

     /**
     * @Route("variables/sendEventsDates/{dateStart}/{dateEnd}", name="sendeventsdates") 
     */
    public function SendEventsDates(EventRepository $repo,$dateStart,$dateEnd){
        /*if(($dateStart && $dateEnd) === undefined){
                    
        }*/
        $dateStart = substr($dateStart,0,10);
        $dateEnd = substr($dateEnd,0,10);
        $events = $repo->findAllBetweenDates($dateStart,$dateEnd);

        $tabl = array();
        
        for ($i=0; $i < sizeof($events); $i++){
            $j = 0;
            $tabl[$i][$j] = $events[$i]->getId();
            $tabl[$i][++$j] = $events[$i]->getTitle();
            $tabl[$i][++$j] = $events[$i]->getDescription();
            $tabl[$i][++$j] = $events[$i]->getUsersid()[0];
            $tabl[$i][++$j] = $events[$i]->getMachinesid()[0];
            $tabl[$i][++$j] = $events[$i]->getDateStart();
            $tabl[$i][++$j] = $events[$i]->getDateEnd();
            $tabl[$i][++$j] = $events[$i]->getFrequence();
        }

        //dd($tabl);
        $message = json_encode($tabl);
        echo ($message);
        return $this->render('ajax_receive/index.html.twig');
    }
}