<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{
    /**
     * Le modèle associé au contrôleur
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;
    
    /**
     * La classe du validateur pour la création
     *
     * @var string
     */
    protected $validatorClass;
    
    /**
     * Les relations à charger automatiquement
     *
     * @var array
     */
    protected $with = [];
    
    /**
     * Nom de la ressource pour les messages
     *
     * @var string
     */
    protected $resourceName;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $this->model::with($this->with);
        
        // Appliquer les filtres si la méthode existe
        if (method_exists($this, 'applyFilters')) {
            $query = $this->applyFilters($query, $request);
        }
        
        // Pagination
        $perPage = $request->input('per_page', 15);
        $items = $query->paginate($perPage);
        
        return $this->sendResponse($items, 'Liste des ' . $this->resourceName);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation
        $validator = $this->validateRequest($request);
        
        if ($validator->fails()) {
            return $this->sendError('Erreur de validation', $validator->errors(), 422);
        }
        
        // Création
        $data = $request->all();
        
        // Pré-traitement des données si nécessaire
        if (method_exists($this, 'beforeStore')) {
            $data = $this->beforeStore($data, $request);
        }
        
        $item = $this->model::create($data);
        
        // Charger les relations
        if (!empty($this->with)) {
            $item->load($this->with);
        }
        
        return $this->sendResponse($item, $this->resourceName . ' créé avec succès', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $item = $this->model::with($this->with)->findOrFail($id);
        return $this->sendResponse($item, 'Détails du ' . $this->resourceName);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        
        // Validation
        $validator = $this->validateRequest($request, $id);
        
        if ($validator->fails()) {
            return $this->sendError('Erreur de validation', $validator->errors(), 422);
        }
        
        // Mise à jour
        $data = $request->all();
        
        // Pré-traitement des données si nécessaire
        if (method_exists($this, 'beforeUpdate')) {
            $data = $this->beforeUpdate($data, $request, $item);
        }
        
        $item->update($data);
        
        // Recharger les relations
        if (!empty($this->with)) {
            $item->load($this->with);
        }
        
        return $this->sendResponse($item, $this->resourceName . ' mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        
        // Vérifier les contraintes si nécessaire
        if (method_exists($this, 'checkDestroyConstraints')) {
            $constraints = $this->checkDestroyConstraints($item);
            
            if ($constraints !== true) {
                return $this->sendError('Impossible de supprimer', $constraints, 400);
            }
        }
        
        // Suppression
        $item->delete();
        
        return $this->sendResponse(null, $this->resourceName . ' supprimé avec succès');
    }
    
    /**
     * Valider la requête
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $id  ID pour les règles d'unicité
     * @return \Illuminate\Validation\Validator
     */
    protected function validateRequest(Request $request, $id = null)
    {
        if ($this->validatorClass) {
            $validator = new $this->validatorClass($request->all(), $id);
            return $validator->makeValidator();
        }
        
        return Validator::make([], []);
    }
    
    /**
     * Envoyer une réponse de succès
     *
     * @param  mixed  $result
     * @param  string  $message
     * @param  int  $code  Code HTTP
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];
        
        if (!is_null($result)) {
            $response['data'] = $result;
        }
        
        return response()->json($response, $code);
    }
    
    /**
     * Envoyer une réponse d'erreur
     *
     * @param  string  $error
     * @param  array  $errorMessages
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        
        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }
        
        return response()->json($response, $code);
    }
}
