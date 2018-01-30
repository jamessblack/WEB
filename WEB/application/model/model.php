<?php

Class Model
{

    function __construct($db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }


    public function publishContent($idC){
        $statement = $this->db->prepare("UPDATE content SET published=1 WHERE id=:idTarget");
        $statement->bindParam(':idTarget', $idC, PDO::PARAM_INT);
        $statement->execute();
    }

    public function dontPublishContent($idC){
        $statement = $this->db->prepare("UPDATE content SET published=2 WHERE id=:idTarget");
        $statement->bindParam(':idTarget', $idC, PDO::PARAM_INT);
        $statement->execute();

    }

    public function uploadHodnoceni($langRate, $appRate, $benRate, $idContent)
    {
        $statement = $this->db->prepare("UPDATE content_rating SET score_lang=:lang, score_appearance=:appear, score_benefit=:benefit WHERE id=:idTarget AND id_reviewer=:idR");
        $statement->bindParam(':lang', $langRate);
        $statement->bindParam(':appear', $appRate);
        $statement->bindParam(':benefit', $benRate);
        $statement->bindParam(':idTarget', $idContent, PDO::PARAM_INT);
        $statement->bindParam(':idR', $_SESSION['loginReviewer'], PDO::PARAM_INT);
        $statement->execute();
    }
    public function addReviewerToContent($reviewerID, $contentID)
    {
        $statement = $this->db->prepare("INSERT INTO content_rating(id_content, id_reviewer)
            VALUES(:idC, :idR)");
        $statement->bindParam(':idC', $contentID);
        $statement->bindParam(':idR', $reviewerID);
        $statement->execute();
    }

    public function getUnassignedReviewers($idContent)
    {
        $sql = "SELECT id, username FROM registered_user WHERE role=1 AND id NOT IN(SELECT r.id FROM content_rating c LEFT JOIN registered_user r ON c.id_reviewer=r.id WHERE id_content=:idC);";
        $query = $this->db->prepare($sql);
        $query->bindParam(':idC', $idContent);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    public function updateRating($langRate, $appRate, $benRate, $idCont){
        $statement = $this->db->prepare("UPDATE content_rating SET score_lang=:lang, score_appearance=:appear, score_benefit=:benefit WHERE id=:idTarget AND id_reviewer=:idR");
        $statement->bindParam(':lang', $langRate);
        $statement->bindParam(':appear', $appRate);
        $statement->bindParam(':benefit', $benRate);
        $statement->bindParam(':idTarget', $idCont, PDO::PARAM_INT);
        $statement->bindParam(':idR', $_SESSION['loginReviewer'], PDO::PARAM_INT);
        $statement->execute();
    }
    public function listRatingID($idC){
        $sql = "SELECT id_content, score_lang, score_appearance, score_benefit FROM content_rating WHERE id=:idC;";
        $query = $this->db->prepare($sql);
        $query->bindParam(':idC', $idC);
        $query->execute();
        $result = $query->fetch();
        return $result;
    }
    public function listContentRating()
    {
        $sql = "SELECT r.id, r.title, r.description, c.score_lang, c.score_appearance, c.score_benefit FROM content_rating c, content r GROUP BY r.id/*WHERE r.published = 0*/;";
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        foreach ($result as $entry){
            $sql = "SELECT r.username FROM content_rating c LEFT JOIN registered_user r ON c.id_reviewer=r.id WHERE id_content=:idC";
            $query = $this->db->prepare($sql);
            $query->bindParam(':idC', $entry->id);
            $query->execute();
            $recenzenti = $query->fetchAll();
            $entry->pocet_recenzentu = sizeof($recenzenti);
            $entry->recenzenti = $recenzenti;
        }
        return $result;
    }

    public function listContent()
    {
        $sql = "SELECT c.title, c.description, c.file, AVG(o.score_lang) as score_lang, AVG(o.score_appearance) as score_appearance, AVG(o.score_benefit) as score_benefit FROM content c LEFT JOIN registered_user r ON r.id = c.id_author LEFT JOIN content_rating o ON c.id = o.id_content WHERE c.published = 1 GROUP BY c.id;";
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    public function listUsers()
    {
        $sql = "SELECT id, username, email, role, blocked FROM registered_user;";
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    public function listUnpublishedReviewed()
    {
        //title, popis, score_lang, score_appearance, svcore_benefit
        $sql = "SELECT c.id, c.title, c.description, AVG(o.score_lang) as score_lang, AVG(o.score_appearance) as score_appearance, AVG(o.score_benefit) as score_benefit FROM content c LEFT JOIN content_rating o ON c.id = o.id_content WHERE c.published=0 AND score_lang IS NOT NULL AND score_appearance IS NOT NULL AND score_benefit IS NOT NULL GROUP BY c.id HAVING COUNT(id_reviewer)=3;";
        $query = $this->db->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;

    }
    public function listContentToEdit($idRev){
        $sql = "SELECT r.id, r.id_content, c.file, c.title, r.score_lang, r.score_appearance, r.score_benefit FROM content_rating r LEFT JOIN content c ON r.id_content=c.id WHERE id_reviewer=:idR AND score_lang IS NOT NULL AND score_appearance IS NOT NULL AND score_benefit IS NOT NULL AND c.published=0;";
        $query = $this->db->prepare($sql);
        $query->bindParam(':idR', $idRev);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    public function listContentToReview($idRev)
    {
        $sql = "SELECT r.id, r.id_content, c.file, c.title FROM content_rating r LEFT JOIN content c ON r.id_content=c.id WHERE id_reviewer=:idR AND score_lang IS NULL AND score_appearance IS NULL AND score_benefit IS NULL;";
        $query = $this->db->prepare($sql);
        $query->bindParam(':idR', $idRev);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    public function listUserContent($id)
    {
        $sql = "SELECT c.id, c.published, c.title, c.description, c.file, AVG(o.score_lang) as score_lang, AVG(o.score_appearance) as score_appearance, AVG(o.score_benefit) as score_benefit, r.username FROM content c LEFT JOIN registered_user r ON r.id = c.id_author LEFT JOIN content_rating o ON c.id = o.id_content WHERE c.id_author=:id GROUP BY c.id;";
        $query = $this->db->prepare($sql);
        $query->bindParam(':id', $id);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    public function registerFc($username, $password, $email)

    {
        $_SESSION['registerError']="";
        $sql = "SELECT id FROM registered_user WHERE username=:username";
        $query = $this->db->prepare($sql);
        $query->bindParam(':username', $username);
        $query->execute();

        if ($query->rowCount() > 0) {
            $_SESSION['registerError'] = "Toto uživatelské jméno již existuje!";
            return;
        } else {

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $statement = $this->db->prepare("INSERT INTO registered_user(username, password, email, role)
            VALUES(:username, :password, :email, 0)");
            $statement->bindParam(':username', $username);
            $statement->bindParam(':password', $password_hash);
            $statement->bindParam(':email', $email);
            $statement->execute();
            $this->loginFc($username, $password);
            header('location: ' . URL . 'home');
        }
    }

    public function loginFc($username, $claimed_password)
    {
        $_SESSION['loginAdmin'] = false;
        $_SESSION['login'] = false;
        $_SESSION['loginReviewer'] = "";

        $sql = "SELECT id, password, role FROM registered_user WHERE username=:username";
        $query = $this->db->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        if ($result) {
            $id = $result->id;
            $password = $result->password;
            $role = $result->role;


            if (password_verify($claimed_password, $password)) {
                $_SESSION['username'] = $username;
                if($role==2){
                    $_SESSION['login'] = false;
                    $_SESSION['loginReviewer'] = "";
                    $_SESSION['loginAdmin'] = true;
                } else if($role==1) {
                    $_SESSION['login'] = false;
                    $_SESSION['loginReviewer'] = $id;
                    $_SESSION['loginAdmin'] = false;
                } else {
                    $_SESSION['login'] = true;
                    $_SESSION['loginReviewer'] = "";
                    $_SESSION['loginAdmin'] = false;
                }
                $_SESSION['user_id'] = $id;
            } else {
                $_SESSION['login'] = false;
                $_SESSION['loginAdmin'] = false;
                $_SESSION['loginReviewer'] = false;
                $_SESSION['loginError'] = "Špatné heslo";
            }
        } else {
            $_SESSION['loginError'] = "Uživatel neexistuje";
        }
        return;


    }

    public function listUserID($id)
    {
        $sql = "SELECT username, email, role FROM registered_user WHERE id=:idUser;";
        $query = $this->db->prepare($sql);
        $query->bindParam(':idUser', $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch();
        return $result;
    }

    public function listContentID($id)
    {
        $sql = "SELECT title, description, file FROM content WHERE id=:idFile;";
        $query = $this->db->prepare($sql);
        $query->bindParam(':idFile', $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch();
        return $result;
    }

    public function deleteUserFc($usedID)
    {
        $statement = $this->db->prepare("DELETE FROM registered_user WHERE id=:idUser");
        $statement->bindParam(':idUser', $usedID);
        $statement->execute();
    }

    public function BanUserFc($userID)
    {
        $statement = $this->db->prepare("UPDATE registered_user SET blocked=1 WHERE id=:idTarget");
        $statement->bindParam(':idTarget', $userID, PDO::PARAM_INT);
        $statement->execute();
    }

    public function editUserRoleFc($target_new, $target_id)
    {
        $statement = $this->db->prepare("UPDATE registered_user SET role=:role WHERE id=:idTarget");
        $statement->bindParam(':role', $target_new);
        $statement->bindParam(':idTarget', $target_id, PDO::PARAM_INT);
        $statement->execute();
    }


    public function editFileDescFc($target_new, $target_id)
    {
        $statement = $this->db->prepare("UPDATE content SET description=:descriptionValue WHERE id=:idTarget");
        $statement->bindParam(':descriptionValue', $target_new);
        $statement->bindParam(':idTarget', $target_id);
        $statement->execute();
    }

    public function editFilePathFc($target_new, $target_id)
    {
        $statement = $this->db->prepare("UPDATE content SET file=:filePath WHERE id=:idTarget");
        $statement->bindParam(':filePath', $target_new);
        $statement->bindParam(':idTarget', $target_id);
        $statement->execute();
    }

    public function editFileTitleFc($target_new, $target_id)
    {
        $statement = $this->db->prepare("UPDATE content SET title=:titleValue WHERE id=:idTarget");
        $statement->bindParam(':titleValue', $target_new);
        $statement->bindParam(':idTarget', $target_id);
        $statement->execute();
    }

    public function uploadFc($target_file, $target_filename, $file_description)
    {
        $statement = $this->db->prepare("INSERT INTO content(title, description, file, id_author, published)
            VALUES(:title, :description, :file, :id_author, 0)");
        $statement->bindParam(':title', $target_filename);
        $statement->bindParam(':description', $file_description);
        $statement->bindParam(':file', $target_file);
        $statement->bindParam(':id_author', $_SESSION['user_id']);
        //echo $target_filename . PHP_EOL . $file_description . PHP_EOL . $target_file . PHP_EOL . $_SESSION['user_id'];
        $statement->execute();
    }

    public function deleteFileFc($deletefilename)
    {
        $statement = $this->db->prepare("DELETE FROM content WHERE file=:file");
        $statement->bindParam(':file', $deletefilename);
        $statement->execute();
    }
}
