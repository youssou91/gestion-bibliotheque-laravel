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

    // public function ask(Request $request)
    // {
    //     try {
    //         $response = $this->ollama->ask($request->input('question'));
    //         return response()->json(['response' => $response]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    // Uncomment and modify the ask method if you want to handle streaming responses

    // public function ask(Request $request)
    // {
    //     $request->validate([
    //         'prompt' => 'required|string',
    //         'model' => 'sometimes|string',
    //         'stream' => 'sometimes|boolean'
    //     ]);

    //     $model = $request->input('model');
    //     $prompt = $request->input('prompt');
    //     $stream = $request->boolean('stream', false);

    //     try {
    //         if ($stream) {
    //             return $this->ollama->ask($model, $prompt, true);
    //         }

    //         $response = $this->ollama->ask($model, $prompt);
    //         return response()->json([
    //             'response' => $response['response'] ?? '',
    //             'context' => $response['context'] ?? []
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function ask(Request $request)
    {
        try {
            $question = $request->input('question');
            $bookTitle = $request->input('book_title', '');
            $model = 'llama3'; // ou Meta-Llama-3-8B-Instruct

            if (!$question) {
                return response()->json(['error' => 'Aucune question reçue.'], 400);
            }

            $fullPrompt = $bookTitle
                ? "L'utilisateur pose une question sur le livre \"$bookTitle\": $question"
                : $question;

            $response = $this->ollama->ask($model, $fullPrompt);

            return response()->json(['response' => $response]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }



    public function listAgents()
    {
        $agents = $this->ollama->getAgents();

        return view('ai.agents', [
            'agents' => $agents
        ]);
    }
}
