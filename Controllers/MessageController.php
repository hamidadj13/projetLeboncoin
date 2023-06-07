<?php
    //session_start();
    include_once(str_replace("\Controllers", "",__DIR__)."\\Models\\Model.php");

    class MessageController
    {
        private $viewName; // le nom de la vue
        private $parent;

        public function __construct($viewName=NULL)
        {
            // Je récupére le nom de la vue que je dois charger...
            $this->viewName = $viewName;
            // Je sais que toujours le dossier qui contiendra les vues et celui Views
            // $this->parent = construit le chemin en auto vers le dossier contenant les views...
            $this->parent = str_replace("\Controllers", "",__DIR__)."\\Views\\";
          
            if($viewName != NULL){
                $this->loadView();
            }

        }
        
        public function loadView()
        {
            // Etant donné que notre header( en tête ) ne changera jamais entre les views alors
            require_once($this->parent."commons\\header.php");
            // Ici la page qui va changer
            require_once($this->parent.$this->viewName.".php");
            // Etant donné que notre footer ( pied ) ne changera jamais entrre les pages alors
            require_once($this->parent."commons\\footer.php");
        }

        public function newConversation()
        {
            //var_dump($_POST) ; die();
            if (isset($_POST["message"])) 
            {
                if (!empty($_POST["message"])) 
                {
                    $contenu = trim(htmlentities($_POST["message"]));
                    $idEnv = (int)$_SESSION["idU"];
                    $idRec = (int)$_POST["idReceveur"];
                    $idAnn = (int)$_POST["idAnn"];

                    $tabC = [$idAnn, $idEnv, $idRec];
                    $model = new Model();
                    $resultC = $model->insertNewConv($tabC);
                    //var_dump($model->getLastIdConv());die();
                    if ($resultC) 
                    {
                        $lastIdConv = $model->getLastIdConv();
                        if(!$lastIdConv)
                        {
                            $_SESSION["message"] = "Une erreur inattendue est survenue. Veuilllez réessayer !! ";
                            $_SESSION["status"] = "danger";
                            $_SESSION["icone"] = "fa-exclamation-circle";
                            header(sprintf("Location: %sdetail/%s",$GLOBALS['__HOST__'],($_POST["idAnn"] * 6895)));
                        }
                        else
                        {
                            $tabM =[$idEnv, $idRec, $lastIdConv, $contenu];
                            $resultM = $model->insertNewMsg($tabM);

                            if ($resultM) 
                            {
                                $_SESSION["message"] = "Votre messsage a été envoyé avec succès !! ";
                                $_SESSION["status"] = "success";
                                $_SESSION["icone"] = "fa-check-circle";
                                header(sprintf("Location: %sdetail/%s",$GLOBALS['__HOST__'],($_POST["idAnn"] * 6895)));
                            } 
                            else 
                            {
                                $_SESSION["message"] = "Une erreur inattendue est survenue lors de l'ajout du message. Veuilllez réessayer !! ";
                                $_SESSION["status"] = "danger";
                                $_SESSION["icone"] = "fa-exclamation-circle";
                                header(sprintf("Location: %sdetail/%s",$GLOBALS['__HOST__'],($_POST["idAnn"] * 6895)));
                            }
                            
                        }
                    } 
                    else 
                    {
                        $_SESSION["message"] = "Erreur lors de la création de la conversation. Veuilllez réessayer !! ";
                        $_SESSION["status"] = "danger";
                        $_SESSION["icone"] = "fa-exclamation-circle";
                        header(sprintf("Location: %sdetail/%s",$GLOBALS['__HOST__'],($_POST["idAnn"] * 6895)));
                    }
                }
                else 
                {
                    $_SESSION["message"] = "Veuillez entrer un messsage valide!! ";
                    $_SESSION["status"] = "danger";
                    $_SESSION["icone"] = "fa-exclamation-circle";
                    header(sprintf("Location: %sdetail/%s",$GLOBALS['__HOST__'],($_POST["idAnn"] * 6895)));
                }
            } 
            else 
            {
                $_SESSION["message"] = "Veuillez entrer un messsage!! ";
                $_SESSION["status"] = "danger";
                $_SESSION["icone"] = "fa-exclamation-circle";
                header(sprintf("Location: %sdetail/%s",$GLOBALS['__HOST__'],($_POST["idAnn"] * 6895)));
            }
            
        }

        public function getAllConversations()
        {
            $id = $_SESSION["idU"];
            $model = new Model();
            $GLOBALS["listConv"] = $result = $model->getListConv($id);
            
            if ($result) 
            {
                $maTabMes = array();
                $maTabUtil = array();

                foreach ($result as $uneConv) 
                {
                    $result2 = $model->getLastMsg($uneConv["idConversation"]);
                    $maTabMes[$uneConv["idConversation"]] = $result2;

                    $idCorrespondant = ($result2["idSender"] == $id) ? $result2["idSender"] : $result2["idReceiver"];
                    $result3 = $model->getUserById($idCorrespondant); 
                    $maTabUtil[$uneConv["idConversation"]] = $result3;
                }
                //var_dump($maTabMes, $maTabUtil ); die();
                $GLOBALS["maTabMes"] = $maTabMes;
                $GLOBALS["maTabUtil"] = $maTabUtil;

                $this->viewName = "messages";
                $this->loadView();
            }
            else 
            {
                echo "Aucune conversation"; die();
            } 
                    
        }
    }