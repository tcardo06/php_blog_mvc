<?php

namespace TestProject\Controller;
use TestProject\Model\Comment;
use TestProject\Model\Tag;

class Blog
{
    const MAX_POSTS = 5;

    protected $oUtil, $oPostModel, $oCommentModel, $oTagModel;
    private $_iId;

    public function __construct()
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $this->oUtil = new \TestProject\Engine\Util;
            $sessionData = $this->oUtil->getSessionData();
            $this->oUtil->isLogged = $sessionData['isLogged'];
            $this->oUtil->userName = $sessionData['userName'];
            $this->oUtil->role = $sessionData['role'];

            $this->oUtil->getModel('Post');
            $this->oPostModel = new \TestProject\Model\Post;

            $this->oCommentModel = new Comment();
            $this->oTagModel = new Tag();

            $this->_iId = (int) (!empty($_GET['id']) ? $_GET['id'] : 0);
        }

    /***** Front end *****/
    // Homepage
    public function index()
    {
      $this->oUtil->oPosts = $this->oPostModel->getPostsWithAuthors();

        // Pass session data to the View
        $this->oUtil->isLogged = $this->oUtil->isLogged();
        $this->oUtil->role = $this->oUtil->getRole();
        $this->oUtil->userName = $this->oUtil->getUserName();

        $this->oUtil->getView('blog');
    }

    public function post()
    {
        $this->oUtil->oPost = $this->oPostModel->getById($this->_iId);
        $this->oUtil->oComments = $this->oCommentModel->getApprovedCommentsWithUsers($this->_iId);
        $this->oUtil->oTags = $this->oTagModel->getTagsByPostId($this->_iId);

        $this->oUtil->getView('post');
    }

    // Handle comment submission
    public function comment()
    {
        if (isset($_POST['submit_comment'])) {
            if (!empty($_POST['comment']) && !empty($_GET['id'])) {
                $comment = htmlspecialchars(trim($_POST['comment']), ENT_QUOTES, 'UTF-8');
                $postId = (int) $_GET['id'];

                if (isset($_SESSION['user_id'])) {
                    $userId = (int) $_SESSION['user_id'];

                    // Prepare and add comment
                    $commentData = [
                        'post_id' => $postId,
                        'user_id' => $userId,
                        'comment' => $comment,
                        'status' => 'pending',
                    ];

                    $result = $this->oCommentModel->addComment($commentData);

                    if ($result) {
                        $_SESSION['message'] = 'Votre commentaire a été soumis avec succès. Il sera publié après approbation.';
                    } else {
                        $_SESSION['error'] = 'Une erreur est survenue lors de l\'envoi du commentaire.';
                    }
                } else {
                    $_SESSION['error'] = 'Vous devez être connecté pour soumettre un commentaire.';
                }
            } else {
                $_SESSION['error'] = 'Le commentaire ne peut pas être vide.';
            }

            header('Location: ' . ROOT_URL . '?p=blog&a=post&id=' . $postId);
            return;
        }
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
            $this->oUtil->oPosts = $this->oPostModel->searchByName($searchQuery);
        } else {
            $this->oUtil->oPosts = $this->oPostModel->getAll();
        }

        // Debug: Check if oPosts is populated
        // var_dump($this->oUtil->oPosts);
        // exit;

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

        $this->oUtil->oPosts = $this->oPostModel->getAll();
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
                $this->oPostModel->setTitle($_POST['title']);
                $this->oPostModel->setBody($_POST['body']);
                $this->oPostModel->setPreview($_POST['preview']);
                $this->oPostModel->setCreatedDate(date('Y-m-d H:i:s'));

                if (isset($_SESSION['user_id'])) {
                    $this->oPostModel->setAuthorId($_SESSION['user_id']);
                } else {
                    $_SESSION['error'] = 'Vous devez être connecté pour ajouter un post.';
                    header('Location: ' . ROOT_URL);
                    return;
                }

                $tagIds = $_POST['tags'] ?? [];

                if ($this->oPostModel->add($tagIds)) {
                    $_SESSION['message'] = 'Post ajouté avec succès!';
                } else {
                    $_SESSION['error'] = 'Une erreur est survenue lors de l\'ajout du post.';
                }
            } else {
                $_SESSION['error'] = 'Tous les champs sont obligatoires et le titre ne peut pas dépasser 255 caractères.';
            }
        }

        $this->oUtil->oTags = $this->oTagModel->getAllTags();
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
                $this->oPostModel->setTitle($_POST['title']);
                $this->oPostModel->setBody($_POST['body']);
                $this->oPostModel->setPreview($_POST['preview']);

                // Set the logged-in user as the author
                if (isset($_SESSION['user_id'])) {
                    $this->oPostModel->setAuthorId($_SESSION['user_id']);
                } else {
                    $_SESSION['error'] = 'Vous devez être connecté pour modifier un post.';
                    header('Location: ' . ROOT_URL);
                    return;
                }

                $tagIds = $_POST['tags'] ?? [];

                if ($this->oPostModel->update($this->_iId, $tagIds)) {
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
        $this->oUtil->oPost = $this->oPostModel->getById($this->_iId);
        $this->oUtil->oTags = $this->oTagModel->getAllTags();
        $this->oUtil->oPost->tags = $this->oTagModel->getTagsByPostId($this->_iId);

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
            if ($this->oPostModel->delete($postId)) {
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

        $this->oUtil->oComments = $this->oCommentModel->getAllCommentsWithPostTitles();
        $this->oUtil->getView('manage_comments');
    }

    public function approveComment()
    {
        if (!$this->isAdmin()) {
            header('Location: ' . ROOT_URL);
            return;
        }

        $commentId = (int) (!empty($_GET['id']) ? $_GET['id'] : 0);

        if ($this->oCommentModel->approveComment($commentId)) {
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

        if ($this->oCommentModel->deleteComment($commentId)) {
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
