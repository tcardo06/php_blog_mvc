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
        $this->oUtil->oPosts = $this->oModel->get(0, self::MAX_POSTS);
        $this->oUtil->getView('blog');
    }

    public function post()
    {
        $this->oUtil->oPost = $this->oModel->getById($this->_iId);
        $this->oUtil->oComments = $this->oModel->getApprovedCommentsWithUsers($this->_iId);
        $this->oUtil->oTags = $this->oModel->getTagsByPostId($this->_iId);

        $this->oUtil->getView('post');
    }

    // Handle comment submission
    public function comment()
    {
        // Check if the comment form was submitted and if the user is logged in
        if (isset($_POST['submit_comment']) && isset($_POST['comment']) && !empty($_SESSION['is_logged'])) {
            // Fetch the post based on the provided post ID
            $this->oUtil->oPost = $this->oModel->getById($this->_iId);

            // Check if the post exists
            if (!empty($this->oUtil->oPost)) {
                // Prepare the comment data
                $commentData = [
                    'post_id' => $this->_iId,
                    'user_id' => $_SESSION['user_id'],
                    'comment' => trim($_POST['comment']),
                    'status' => 'pending',
                ];

                // Try to insert the comment into the database
                if ($this->oModel->addComment($commentData)) {
                    $_SESSION['message'] = 'Commentaire soumis avec succès, en attente de validation.';
                } else {
                    $_SESSION['error'] = 'Une erreur est survenue lors de l\'ajout du commentaire. Veuillez réessayer.';
                }

            } else {
                // If the post is not found, set an error and redirect
                $_SESSION['error'] = 'Le post est introuvable !';
                header('Location: ' . ROOT_URL . '?p=blog&a=all');
                return;
            }
        } else {
            // If the user is not logged in or the comment is missing
            $_SESSION['error'] = 'Veuillez vous connecter pour soumettre un commentaire ou remplir le champ de commentaire.';
        }

        // Reload the same post page after processing the comment to display success or error messages
        header('Location: ' . ROOT_URL . '?p=blog&a=post&id=' . $this->_iId);
        return;
    }

    public function manage()
    {
        if (!$this->isAdmin()) {
            header('Location: ' . ROOT_URL);
            return;
        }

        $searchQuery = !empty($_GET['q']) ? trim($_GET['q']) : '';
        $action = !empty($_GET['action']) ? $_GET['action'] : 'edit'; // Default to edit if no action is provided

        if ($searchQuery) {
            $this->oUtil->oPosts = $this->oModel->searchByName($searchQuery);
        } else {
            $this->oUtil->oPosts = $this->oModel->getAll();
        }

        $this->oUtil->action = $action;
        $this->oUtil->getView('post_list');
    }

    public function notFound()
    {
        $this->oUtil->getView('not_found');
    }

    /***** For Admin (Back end) *****/
    public function all()
    {
        if (!$this->isLogged()) {
            return;
        }

        $this->oUtil->oPosts = $this->oModel->getAll();
        $this->oUtil->getView('index');
    }

    public function add()
    {
        if (!$this->isAdmin()) {
            header('Location: ' . ROOT_URL);
            return;
        }

        if (!empty($_POST['add_submit'])) {
            if (isset($_POST['title'], $_POST['body'], $_POST['preview']) && mb_strlen($_POST['title']) <= 255) {
                // Use setter methods from the Blog model to set values
                $this->oModel->setTitle($_POST['title']);
                $this->oModel->setBody($_POST['body']);
                $this->oModel->setPreview($_POST['preview']);
                $this->oModel->setCreatedDate(date('Y-m-d H:i:s'));

                $tagIds = $_POST['tags'] ?? [];

                if ($this->oModel->add($tagIds)) {
                    $_SESSION['message'] = 'Post ajouté avec succès!';
                } else {
                    $_SESSION['error'] = 'Une erreur est survenue lors de l\'ajout du post. Veuillez réessayer plus tard.';
                }
            } else {
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
            return;
        }

        if (!empty($_POST['edit_submit'])) {
            if (isset($_POST['title'], $_POST['body'], $_POST['preview'])) {
                $this->oModel->setTitle($_POST['title']);
                $this->oModel->setBody($_POST['body']);
                $this->oModel->setPreview($_POST['preview']);

                $tagIds = $_POST['tags'] ?? [];

                if ($this->oModel->update($this->_iId, $tagIds)) {
                    $_SESSION['message'] = 'Post mis à jour avec succès!';
                } else {
                    $_SESSION['error'] = 'Erreur lors de la mise à jour du post.';
                }

                // Redirect to avoid form resubmission
                header('Location: ' . ROOT_URL . '?p=blog&a=edit&id=' . $this->_iId);
                return;
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
            return;
        }

        $postId = (int) (!empty($_GET['id']) ? $_GET['id'] : 0);

        if (!empty($_POST['delete']) && $postId > 0) {
            if ($this->oModel->delete($postId)) {
                $_SESSION['message'] = 'Post supprimé avec succès!';
            } else {
                $_SESSION['error'] = 'Erreur lors de la suppression du post.';
            }
        } else {
            $_SESSION['error'] = 'ID de post invalide ou aucune action de suppression détectée.';
        }

        // Redirect back to the manage page
        header('Location: ' . ROOT_URL . '?p=blog&a=manage&action=delete');
        return;
    }

    public function manageComments()
    {
        if (!$this->isAdmin()) {
            header('Location: ' . ROOT_URL);
            return;
        }

        // Get all comments with the associated post titles
        $this->oUtil->oComments = $this->oModel->getAllCommentsWithPostTitles();
        $this->oUtil->getView('manage_comments');
    }

    public function approveComment()
    {
        if (!$this->isAdmin()) {
            header('Location: ' . ROOT_URL);
            return;
        }

        $commentId = (int) (!empty($_GET['id']) ? $_GET['id'] : 0);

        if ($this->oModel->approveComment($commentId)) {
            $_SESSION['message'] = 'Commentaire approuvé avec succès !';
        } else {
            $_SESSION['error'] = 'Erreur lors de l\'approbation du commentaire.';
        }

        header('Location: ' . ROOT_URL . '?p=blog&a=manageComments');
        return;
    }

    public function deleteComment()
    {
        if (!$this->isAdmin()) {
            header('Location: ' . ROOT_URL);
            return;
        }

        $commentId = (int) (!empty($_GET['id']) ? $_GET['id'] : 0);

        if ($this->oModel->deleteComment($commentId)) {
            $_SESSION['message'] = 'Commentaire supprimé avec succès !';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression du commentaire.';
        }

        header('Location: ' . ROOT_URL . '?p=blog&a=manageComments');
        return;
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
