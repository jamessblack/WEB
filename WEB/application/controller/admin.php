<?php
class Admin extends Controller
{
    public function index()
    {

        $_SESSION['editUserID'] = "";
        $_SESSION['priraditID'] = "";
        $_SESSION['banUserID'] = "";
        $_SESSION['deleteUserID'] = "";
        $_SESSION['UpdateUserID'] = "";
        $_SESSION['editRole'] = "";

        if(isset($_POST['editRole'])){
            $_SESSION['editRole'] = $_POST['editRole'];
        }
        if(isset($_POST['editUserID'])){
            $_SESSION['editUserID'] = $_POST['editUserID'];
            $this->editUser();
        }
        if(isset($_POST['banUserID'])){
            $_SESSION['banUserID'] = $_POST['banUserID'];
            $this->banUser();
        }
        if(isset($_POST['deleteUserID'])){
            $_SESSION['deleteUserID'] = $_POST['deleteUserID'];
            $this->deleteUser();
        }
        if(isset($_POST['idUserToEdit'])){
            $_SESSION['UpdateUserID'] = $_POST['idUserToEdit'];
            $this->updateUser();

        }

        $users = $this->model->listUsers();

        // load views

        if(!isset($_POST['editUserID'])) {
            require APP . 'view/_templates/header_admin.php';
            require APP . 'view/admin/index.phtml';
            require APP . 'view/_templates/footer.php';
        }
    }

    public function publishing(){

        if(isset($_POST['publishID'])){
            $this->model->publishContent($_POST['publishID']);
        } else if(isset($_POST['publishNotID'])){
            $this->model->dontPublishContent($_POST['publishNotID']);
        }
        $content = $this->model->listUnpublishedReviewed();
        require APP . 'view/_templates/header_admin.php';
        require APP . 'view/admin/publishing.phtml';
        require APP . 'view/_templates/footer.php';
    }

    public function contribution()
    {

        $_SESSION['idReviewerToAdd'] = "";
        if(isset($_POST['priraditID'])){
            $_SESSION['priraditID'] = $_POST['priraditID'];
            $this->addReviewer();
        }
        else {
            if (isset($_POST['idReviewerToAdd']) && isset($_POST['idContent'])) {
                $this->model->addReviewerToContent($_POST['idReviewerToAdd'],$_POST['idContent']);
            }

            $contributions = $this->model->listContentRating();
            require APP . 'view/_templates/header_admin.php';
            require APP . 'view/admin/contribution.phtml';
            require APP . 'view/_templates/footer.php';
        }
    }

    public function addReviewer()
    {
        $idContent =  $_SESSION['priraditID'];
        $unassigned = $this->model->getUnassignedReviewers($_SESSION['priraditID']);
        $_SESSION['priraditID']="";
        require APP . 'view/_templates/header_admin.php';
        require APP . 'view/admin/addReviewer.phtml';
        require APP . 'view/_templates/footer.php';
    }

    public function deleteUser()
    {
        $deleteUserID = $_SESSION['deleteUserID'];
        $_SESSION['deleteUserID'] = "";
        $this->model->deleteUserFc($deleteUserID);
    }

    public function banUser()
    {
        if($_SESSION['banUserID'] != ""){
            $this->model->BanUserFc($_SESSION['banUserID']);
        }

        $_SESSION['editRole'] = "";
        $_SESSION['UpdateUserID'] = "";
    }

    public function editUser()
    {
        $contribution = $this->model->listUserID($_SESSION['editUserID']);
        $idEdit = $_SESSION['editUserID'];
        $_SESSION['editUserID'] = "";
        if($_SESSION['loginAdmin']){
            require APP . 'view/_templates/header_admin.php';
        } else {
            require APP . 'view/_templates/header_login.php';
        }
        require APP . 'view/admin/edit.phtml';
        require APP . 'view/_templates/footer.php';
    }

    public function updateUser()
    {
        if($_SESSION['editRole'] != ""){
            $this->model->editUserRoleFc($_SESSION['editRole'], $_SESSION['UpdateUserID']);
        }

        $_SESSION['editRole'] = "";
        $_SESSION['UpdateUserID'] = "";
    }




}
