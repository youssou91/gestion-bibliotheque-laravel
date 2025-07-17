<?php

namespace App\Http\Controllers;

use App\Services\OllamaService;
use Illuminate\Http\Request;

class AIController extends Controller
{
    public function __construct(
        protected OllamaService $ollama
    ) {}

    // Dans AIController.php
    public function showChat(Request $request)
    {
        $selectedAgent = $request->query('agent');
        $models = $this->ollama->getModels();
        $agents = $this->ollama->getAgents();

        return view('ai.chat', [
            'models' => $models,
            'agents' => $agents,
            'selectedAgent' => $selectedAgent
        ]);
    }

    
    public function ask(Request $request)
    {
        try {
            $question = $request->input('question');
            $bookTitle = $request->input('book_title', '');
            $model = 'tinyllama:latest'; // modèle léger pour faible RAM

            if (!$question) {
                return response()->json(['error' => 'Aucune question reçue.'], 400);
            }

            // Récupérer seulement 2 ouvrages de la base, champs limités
            $ouvrages = \App\Models\Ouvrages::limit(2)->get(['titre', 'auteur']);

            // Formater les infos ouvrages pour le prompt
            $ouvragesPrompt = $ouvrages->map(function($ouvrage) {
                return "Titre: {$ouvrage->titre}\nAuteur: {$ouvrage->auteur}";
            })->implode("\n---\n");

            $intro = "Voici quelques ouvrages de la base :\n" . $ouvragesPrompt . "\n\n";

            $fullPrompt = $intro;
            if ($bookTitle) {
                $fullPrompt .= "L'utilisateur pose une question sur le livre \"$bookTitle\": $question";
            } else {
                $fullPrompt .= $question;
            }

            $response = $this->ollama->ask($model, $fullPrompt);

            \Log::info('Structure réponse Ollama', ['type' => gettype($response), 'content' => $response]);

            // Gestion robuste de la réponse pour le front
            $text = '';
            if (is_array($response)) {
                if (isset($response['response'])) {
                    $text = $response['response'];
                } elseif (isset($response['message'])) {
                    $text = $response['message'];
                } else {
                    $text = json_encode($response);
                }
            } elseif (is_string($response)) {
                $text = $response;
            } else {
                $text = 'Réponse IA vide ou inconnue.';
            }

            return response()->json(['response' => $text]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }

   
}
