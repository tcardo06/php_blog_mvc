<?php

namespace TestProject\Controller;

class Blog
{
    const MAX_POSTS = 5;

    protected $oUtil, $oModel;
    private $_iId;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->oUtil = new \TestProject\Engine\Util;

        /** Get the Model class in all the controller classes **/
        $this->oUtil->getModel('Blog');
        $this->oModel = new \TestProject\Model\Blog;

        /** Get the Post ID in the constructor in order to avoid the duplication of the same code **/
        $this->_iId = (int) (!empty($_GET['id']) ? $_GET['id'] : 0);
    }

    /***** Front end *****/
    // Homepage
    public function index()
    {
        $this->oUtil->oPosts = $this->oModel->get(0, self::MAX_POSTS); // Get only the latest X posts

        $this->oUtil->getView('index');
    }

    public function post()
    {
        $this->oUtil->oPost = $this->oModel->getById($this->_iId);
        $this->oUtil->oComments = $this->oModel->getApprovedComments($this->_iId);
        $this->oUtil->oTags = $this->oModel->getTagsByPostId($this->_iId);

        $this->oUtil->getView('post');
    }

    // Handle comment submission
    public function comment()
    {
        // Check if the user is logged in and form data exists
        if (!empty($_POST['submit_comment']) && !empty($_POST['comment']) && !empty($_SESSION['is_logged'])) {
            // Ensure the post ID exists
            if ($this->_iId > 0) {
                $commentData = [
                    'post_id' => $this->_iId,
                    'user_id' => $_SESSION['user_id'], // Assuming this is stored in the session
                    'comment' => $_POST['comment'],
                    'status' => 'pending',
                ];

                // Insert the comment
                $this->oModel->addComment($commentData);

                echo "Tentative d'insertion de commentaire.";
                exit; // Stop further execution
            } else {
                echo 'ID de post invalide.';
            }
        } else {
            echo 'Veuillez vous connecter pour soumettre un commentaire ou remplir le texte du commentaire.';
        }
    }

    public function manage()
    {
        if (!$this->isAdmin()) {
            header('Location: ' . ROOT_URL);
            exit;
        }

        $searchQuery = !empty($_GET['q']) ? trim($_GET['q']) : '';
        $action = !empty($_GET['action']) ? $_GET['action'] : 'edit'; // Default to edit if no action is provided

        // If there's a search query, get posts by name; otherwise, get all posts
        if ($searchQuery) {
            $this->oUtil->oPosts = $this->oModel->searchByName($searchQuery);
        } else {
            $this->oUtil->oPosts = $this->oModel->getAll();
        }

        $this->oUtil->action = $action; // Pass the action to the view
        $this->oUtil->getView('post_list');
    }

    public function notFound()
    {
        $this->oUtil->getView('not_found');
    }

    /***** For Admin (Back end) *****/
    public function all()
    {
        if (!$this->isLogged()) exit;

        $this->oUtil->oPosts = $this->oModel->getAll();

        $this->oUtil->getView('index');
    }

    public function add()
    {
        if (!$this->isAdmin()) {
            header('Location: ' . ROOT_URL);
            exit;
        }

        if (!empty($_POST['add_submit']))
        {
            if (isset($_POST['title'], $_POST['body'], $_POST['preview']) && mb_strlen($_POST['title']) <= 255)
            {
                $aData = array(
                    'title' => $_POST['title'],
                    'body' => $_POST['body'],
                    'preview' => $_POST['preview'],
                    'created_date' => date('Y-m-d H:i:s')
                );

                $tagIds = $_POST['tags'] ?? [];

                if ($this->oModel->add($aData, $tagIds)) {
                    $_SESSION['message'] = 'Post ajouté avec succès!';
                } else {
                    $_SESSION['error'] = 'Une erreur est survenue lors de l\'ajout du post. Veuillez réessayer plus tard.';
                }
            }
            else
            {
                $_SESSION['error'] = 'Tous les champs sont obligatoires et le titre ne peut pas dépasser 255 caractères.';
            }
        }

        // Fetch all tags from the database
        $this->oUtil->oTags = $this->oModel->getAllTags();
        $this->oUtil->getView('add_post');
    }

    public function edit()
    {
        if (!$this->isAdmin()) {
            header('Location: ' . ROOT_URL);
            exit;
        }

        if (!empty($_POST['edit_submit'])) {
            if (isset($_POST['title'], $_POST['body'], $_POST['preview'])) {
                $aData = array(
                    'post_id' => $this->_iId,
                    'title' => $_POST['title'],
                    'body' => $_POST['body'],
                    'preview' => $_POST['preview']
                );

                // Get selected tag IDs
                $tagIds = $_POST['tags'] ?? [];

                if ($this->oModel->update($aData, $tagIds)) {
                    $_SESSION['message'] = 'Post mis à jour avec succès!';
                } else {
                    $_SESSION['error'] = 'Erreur lors de la mise à jour du post.';
                }

                // Redirect to avoid form resubmission
                header('Location: ' . ROOT_URL . '?p=blog&a=edit&id=' . $this->_iId);
                exit;
            } else {
                $_SESSION['error'] = 'Tous les champs sont obligatoires.';
            }
        }

        // Fetch the post and tags data for the form
        $this->oUtil->oPost = $this->oModel->getById($this->_iId);
        $this->oUtil->oTags = $this->oModel->getAllTags();
        $this->oUtil->oPost->tags = $this->oModel->getPostTags($this->_iId);

        $this->oUtil->getView('edit_post');
    }

    public function delete()
    {
        if (!$this->isAdmin()) {
            header('Location: ' . ROOT_URL);
            exit;
        }

        $postId = (int) (!empty($_GET['id']) ? $_GET['id'] : 0); // Capture the post ID from the URL

        if (!empty($_POST['delete']) && $postId > 0) {
            if ($this->oModel->delete($postId)) {
                $_SESSION['message'] = 'Post supprimé avec succès!';
            } else {
                $_SESSION['error'] = 'Erreur lors de la suppression du post.';
            }
        } else {
            $_SESSION['error'] = 'ID de post invalide ou aucune action de suppression détectée.';
        }

        // Redirect back to the manage page to show the success or error message
        header('Location: ' . ROOT_URL . '?p=blog&a=manage&action=delete');
        exit;
    }

    protected function isLogged()
    {
        return !empty($_SESSION['is_logged']);
    }

    protected function isAdmin()
    {
        return $this->isLogged() && isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin';
    }
}
