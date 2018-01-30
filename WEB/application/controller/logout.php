<?php
class Logout extends Controller
{
    public function index()
    {
        $_SESSION['login']=false;
        $_SESSION['loginAdmin'] = false;
        $_SESSION['loginReviewer'] = "";
        $_SESSION['user_id']=-1;
        $_SESSION['username']="";

        // load views
        require APP . 'view/_templates/header.php';
        require APP . 'view/home/logout.phtml';
        require APP . 'view/_templates/footer.php';
    }




}
