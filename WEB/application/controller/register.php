<?php
class Register extends Controller
{
    public function index()
    {

        $_SESSION['registerError'] = "";
        if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['passwordCh']) && isset($_POST['email'])) {
            if($_POST['password'] == $_POST['passwordCh']) {
                $this->model->registerFc($_POST['username'], $_POST['password'], $_POST['email']);
            } else {
                $_SESSION['registerError'] = "Hesla se neshodují";
            }
        }

        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/register/index.phtml';
        require APP . 'view/_templates/footer.php';
    }




}
