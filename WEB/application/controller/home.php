<?php
class Home extends Controller
{
    public function index()
    {
        $_SESSION['loginError'] = "";
        $_SESSION['loadFile'] = "";
	    $contents = $this->model->listContent();
        if(isset($_POST['path']))
        {
            $_SESSION['loadFile'] = $_POST['path'];
            $_POST['path'] = "";
            $this->loadPDF();
        }

        // load views
        if($_SESSION['login'])
        {
            require APP . 'view/_templates/header_login.php';
        }
        else if($_SESSION['loginAdmin']){
            require APP . 'view/_templates/header_admin.php';
        } else if($_SESSION['loginReviewer'] != "") {
            require APP . 'view/_templates/header_recenzent.php';
        } else {
            require APP . 'view/_templates/header.php';
        }
        require APP . 'view/home/index.phtml';
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
