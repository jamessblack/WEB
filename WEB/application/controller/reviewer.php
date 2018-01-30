<?php
class Reviewer extends Controller
{
    public function index(){
        $_SESSION['loadFile'] = "";
        $_SESSION['reviewID'] = "";
        if(isset($_POST['langRate']) && isset($_POST['appRate']) && isset($_POST['benRate']) && isset($_POST['idContent'])) {
            $this->model->uploadHodnoceni($_POST['langRate'], $_POST['appRate'], $_POST['benRate'], $_POST['idContent']);
        }
        if(isset($_POST['path'])){
            $_SESSION['loadFile'] = $_POST['path'];
            $this->loadPDF();
        }
        if(isset($_POST['reviewID']) && isset($_POST['reviewPath'])){
            $_SESSION['reviewID'] = $_POST['reviewID'];
            $_SESSION['reviewPath'] = $_POST['reviewPath'];
            $this->ohodnotit();
        } else {
            $contents = $this->model->listContentToReview($_SESSION['loginReviewer']);
            require APP . 'view/_templates/header_recenzent.php';
            require APP . 'view/reviewer/index.phtml';
            require APP . 'view/_templates/footer.php';
        }
    }

    public function editRating(){
        if(isset($_POST['path'])){
            $_SESSION['loadFile'] = $_POST['path'];
            $this->loadPDF();
        }

        if(isset($_POST['idContent']) && isset($_POST['langRate']) && isset($_POST['appRate']) && isset($_POST['benRate'])){
            $this->model->updateRating($_POST['langRate'], $_POST['appRate'], $_POST['benRate'], $_POST['idContent']);
        }

        if(isset($_POST['editID'])) {
            $idCont = $_POST['editID'];
            $content = $this->model->listRatingID($idCont);
            require APP . 'view/_templates/header_recenzent.php';
            require APP . 'view/reviewer/edit.phtml';
            require APP . 'view/_templates/footer.php';
        } else {
            $contents = $this->model->listContentToEdit($_SESSION['loginReviewer']);
            require APP . 'view/_templates/header_recenzent.php';
            require APP . 'view/reviewer/editRating.phtml';
            require APP . 'view/_templates/footer.php';
        }
    }

    public function ohodnotit(){
        $idCont = $_SESSION['reviewID'];
        $_SESSION['reviewID'] = "";
        require APP . 'view/_templates/header_recenzent.php';
        require APP . 'view/reviewer/rating.phtml';
        require APP . 'view/_templates/footer.php';
    }

    public function loadPDF()
    {
        $openfilename = "uploads/" . $_SESSION['loadFile'];
        $_SESSION['loadFile'] = "";
        header("Content-type: application/pdf");
        header("Content-Length: " . filesize(APP .$openfilename));
        readfile( APP . $openfilename);
    }
}