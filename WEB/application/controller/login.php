<?php
class Login extends Controller
{
    public function index()
    {
        if($_SESSION['login']==true){
            header('location: ' . URL . 'contribution');
        }
        if($_SESSION['loginAdmin']==true){
            header('location: ' . URL . 'admin');
        }
        if($_SESSION['loginReviewer'] !=""){
            header('location: ' . URL . 'reviewer');
        }
        if(isset($_POST['lusername']) && isset($_POST['lpassword'])) {
            $_SESSION['loginError'] = "";

            $this->model->loginFc($_POST['lusername'], $_POST['lpassword']);
            if($_SESSION['login']){
                header('location: ' . URL . 'contribution');
            }
            if($_SESSION['loginAdmin']){
                header('location: ' . URL . 'admin');
            }
            if($_SESSION['loginReviewer'] != ""){
                header('location: ' . URL . 'reviewer');
            }
        }

        // load views
        if($_SESSION['login'])
        {
            require APP . 'view/_templates/header_login.php';
        }
        else
            {
            require APP . 'view/_templates/header.php';
        }
        require APP . 'view/login/index.phtml';
        require APP . 'view/_templates/footer.php';
    }




}
