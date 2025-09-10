<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;

class BaseValidator
{
    /**
     * Règles de validation
     *
     * @var array
     */
    protected $rules = [];
    
    /**
     * Messages d'erreur personnalisés
     *
     * @var array
     */
    protected $messages = [];
    
    /**
     * Créer une instance de validateur
     *
     * @param  array  $data
     * @param  int|null  $id  ID pour les règles d'unicité
     * @return \Illuminate\Validation\Validator
     */
    public function makeValidator(array $data, $id = null)
    {
        // Personnaliser les règles si nécessaire
        $rules = $this->customizeRules($this->rules, $id);
        
        return Validator::make($data, $rules, $this->messages);
    }
    
    /**
     * Personnaliser les règles de validation
     *
     * @param  array  $rules
     * @param  int|null  $id
     * @return array
     */
    protected function customizeRules(array $rules, $id = null)
    {
        // Parcourir les règles pour ajouter l'ID aux règles d'unicité
        if ($id !== null) {
            foreach ($rules as $field => $rule) {
                if (is_string($rule) && str_contains($rule, 'unique:')) {
                    $rules[$field] = $this->addIdToUniqueRule($rule, $id);
                } elseif (is_array($rule)) {
                    $rules[$field] = array_map(function($r) use ($id) {
                        return is_string($r) && str_contains($r, 'unique:') 
                            ? $this->addIdToUniqueRule($r, $id) 
                            : $r;
                    }, $rule);
                }
            }
        }
        
        return $rules;
    }
    
    /**
     * Ajouter l'ID à une règle d'unicité
     *
     * @param  string  $rule
     * @param  int  $id
     * @return string
     */
    protected function addIdToUniqueRule($rule, $id)
    {
        // Extraire les parties de la règle
        $parts = explode('|', $rule);
        
        foreach ($parts as &$part) {
            if (str_starts_with(trim($part), 'unique:')) {
                // Ajouter l'ID à la règle d'unicité
                $part = rtrim($part, ',') . ",{$id},id";
            }
        }
        
        return implode('|', $parts);
    }
    
    /**
     * Valider les données
     *
     * @param  array  $data
     * @param  int|null  $id
     * @return \Illuminate\Validation\Validator
     */
    public function validate(array $data, $id = null)
    {
        return $this->makeValidator($data, $id);
    }
}
