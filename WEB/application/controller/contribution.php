<?php
class Contribution extends Controller
{
    public function index()
    {
        $_SESSION['loadFile'] = "";
        $_SESSION['deleteFile'] = "";
        $_SESSION['editID'] = "";
        $_SESSION['editDescription'] = "";
        $_SESSION['editFilename'] = "";
        $_SESSION['editPath'] = "";
        $_SESSION['idFileToEdit'] = "";

        if(isset($_POST['editFileDescription'])){
            $_SESSION['editDescription'] = $_POST['editFileDescription'];
        }
        if(isset($_POST['editFilename'])){
            $_SESSION['editFilename'] = $_POST['editFilename'];
        }
        if(isset($_POST['fileToEdit'])){
            $_SESSION['editPath'] = $_POST['fileToEdit'];
        }
        if(isset($_POST['idFileToEdit'])){
            $_SESSION['idFileToEdit'] = $_POST['idFileToEdit'];
            $this->editFile();
        }
        if(isset($_POST['path']))
        {
            $_SESSION['loadFile'] = $_POST['path'];
            $this->loadPDF();
        }
        if(isset($_POST['deletePath']))
        {
            $_SESSION['deleteFile'] = $_POST['deletePath'];
            $this->deleteFile();
        }
        if(isset($_POST['editID']))
        {
            $_SESSION['editID'] = $_POST['editID'];
            $this->edit();
        }
        $contributions = $this->model->listUserContent($_SESSION['user_id']);

        // load views
        if(!isset($_POST['editID'])) {
            if($_SESSION['loginAdmin']){
                require APP . 'view/_templates/header_admin.php';
            } else {
                require APP . 'view/_templates/header_login.php';
            }
            require APP . 'view/contribution/index.phtml';
            require APP . 'view/_templates/footer.php';
        }
    }

    public function loadPDF()
    {
        $openfilename = "uploads/" . $_SESSION['loadFile'];
        $_SESSION['loadFile'] = "";
        header("Content-type: application/pdf");
        header("Content-Length: " . filesize(APP .$openfilename));
        readfile( APP . $openfilename);
    }

    public function deleteFile()
    {
        $delete_dir = APP . "uploads/";
        $deletefilename = $_SESSION['deleteFile'];
        $_SESSION['deleteFile'] = "";
        if($deletefilename != "")
        {
            unlink($delete_dir . $deletefilename);
            $this->model->deleteFileFc($deletefilename);
        }
    }

    public function edit()
    {
        $contribution = $this->model->listContentID($_SESSION['editID']);
        $idkedit = $_SESSION['editID'];
        $_SESSION['editID'] = "";
        if($_SESSION['loginAdmin']){
            require APP . 'view/_templates/header_admin.php';
        } else {
            require APP . 'view/_templates/header_login.php';
        }
        require APP . 'view/contribution/edit.phtml';
        require APP . 'view/_templates/footer.php';
    }

    public function editFile()
    {
        if(isset($_FILES["fileToEdit"])){
            $editContribution = $this->model->listContentID($_SESSION['idFileToEdit']);
            $targetToDelete = $editContribution->file;
            $target_dir = APP . "uploads/";
            $target_file_relative = basename($_FILES["fileToEdit"]["name"]);
            $target_file = $target_dir . basename($_FILES["fileToEdit"]["name"]);
            if (!move_uploaded_file($_FILES["fileToEdit"]["tmp_name"], $target_file)) {
                $_SESSION['edit_error'] = "Chyba při nahrávání souboru.";
            } else {
                $this->model->editFilePathFc($target_file_relative, $_SESSION['idFileToEdit']);
                unlink($target_dir . $targetToDelete);
            }
        }
        if($_SESSION['editDescription'] != ""){
            $this->model->editFileDescFc($_SESSION['editDescription'], $_SESSION['idFileToEdit']);
        }
        if($_SESSION['editFilename'] != ""){
            $this->model->editFileTitleFc($_SESSION['editFilename'], $_SESSION['idFileToEdit']);
        }

        $_SESSION['editDescription'] = "";
        $_SESSION['editFilename'] = "";
        $_SESSION['editPath'] = "";
        $_SESSION['idFileToEdit'] = "";
        //$_SESSION['editFilename'] = "";
        //$_SESSION['editPath'] = "";
        /*if(isset($_POST['description'])){
            $this->model->editFileFC("description", $_POST['description'], $_SESSION['editID']);
        }*/
        //$_SESSION['test']= $_POST['description'] . $_SESSION['editID'];
        //header('location: ' . URL . 'contribution');
    }

    public function upload()
    {
        if(isset($_FILES["fileToUpload"]) && isset($_POST['description'])){
            $this->uploadFile();
        }
        require APP . 'view/_templates/header_login.php';
        require APP . 'view/contribution/upload.phtml';
        require APP . 'view/_templates/footer.php';
    }
    public function uploadFile()
    {
        $target_dir = APP . "uploads/";
        $target_file_relative = basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $_SESSION['uploadOk'] = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if file already exists
        if (file_exists($target_file)) {
            $_SESSION['upload_error'] = "Soubor již existuje, nelze nahrát znovu.";
            $_SESSION['uploadOk'] = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            $_SESSION['upload_error'] =  "Soubor je příliš veliký, maximální povolená velikost je 5 MB.";
            $_SESSION['uploadOk'] = 0;
        }
        // Allow certain file formats
        if($imageFileType != "pdf") {
            $_SESSION['upload_error'] =  "Soubor není typu pdf.";
            $_SESSION['uploadOk'] = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($_SESSION['uploadOk'] == 1) {
            if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $_SESSION['uploadOk'] = 0;
                $_SESSION['upload_error'] = "Chyba při nahrávání souboru.";
            } else {
                $this->model->uploadFc($target_file_relative, $_POST['filename'], $_POST['description']);
                header('location: ' . URL . 'contribution');
            }
        }
    }


}