<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/todo")]
class ToDoController extends AbstractController
{
    /**
     * @Route("/", name="todo")
     */
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        // Afficher notre tableau de todo
        // sinon je l'initialise puis j'affiche
        if (!$session->has('todos')) {
            $todos = [
                'Achat' => 'Acheter clé usb',
                'Cours' => 'Finaliser mon cours',
                'Correction' => 'Corriger mes examens'
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "La liste des todos viens d'être initialisée");
        }
        // si j'ai mon tableau de to do dans ma session je ne fais que l'afficher
        
        return $this->render('to_do/index.html.twig');
    }

    #[Route(
        '/add/{name?test}/{content?test}', 
        name: 'todo.add'
    )]
    public function addToDo(Request $request, $name, $content): RedirectResponse 
    {
        $session = $request->getSession();
        // Verifier si j'ai mon tableau de todos dans la session
        if ($session->has('todos')) {
            // si oui
            // Vérifier si on a deja un todo avec le meme name
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                // si oui afficher erreur
                $this->addFlash('error', "Le todo d'id $name existe déja dans la liste");
            } else {
                // sinon on l'ajoute et on affiche un message de succés
                $todos[$name] = $content;                
                $this->addFlash('success', "Le todo d'id $name a été ajouté avec succés");
                $session->set('todos', $todos);
            }
        } else {
            // si non
                // afficher une erreur et rediriger vers le controller index
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');                       
    }

    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function updateToDo(Request $request, $name, $content): RedirectResponse 
    {
        $session = $request->getSession();
        // Verifier si j'ai mon tableau de todos dans la session
        if ($session->has('todos')) {
            // si oui
            // Vérifier si on a deja un todo avec le meme name
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                // si oui afficher erreur
                $this->addFlash('error', "Le todo d'id $name n'existe pas dans la liste");
            } else {
                // sinon on l'ajoute et on affiche un message de succés
                $todos[$name] = $content;                
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo d'id $name a été modifié avec succés");
            }
        } else {
            // si non
                // afficher une erreur et rediriger vers le controller index
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');                       
    }

    #[Route('/delete/{name}', name: 'todo.delete')]
    public function deleteToDo(Request $request, $name): RedirectResponse 
    {
        $session = $request->getSession();
        // Verifier si j'ai mon tableau de todos dans la session
        if ($session->has('todos')) {
            // si oui
            // Vérifier si on a deja un todo avec le meme name
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                // si oui afficher erreur
                $this->addFlash('error', "Le todo d'id $name n'existe pas dans la liste");
            } else {
                // sinon on l'ajoute et on affiche un message de succés
                unset($todos[$name]);                
                $session->set('todos', $todos);
                $this->addFlash('success', "Le todo d'id $name a été supprimé avec succés");
            }
        } else {
            // si non
                // afficher une erreur et rediriger vers le controller index
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');                       
    }

    #[Route('/reset', name: 'todo.reset')]
    public function resetToDo(Request $request): RedirectResponse 
    {
        $session = $request->getSession();
        $session->remove('todos');
        return $this->redirectToRoute('todo');                       
    }

}
