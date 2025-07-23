<?php

namespace App\Validation;

/**
 * Validator Class
 * Menangani validasi input data
 */
class Validator
{
    protected $data;
    protected $rules;
    protected $messages;
    protected $errors = [];
    protected $validated = [];
    
    public function __construct($data, $rules, $messages = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->messages = $messages;
        
        $this->validate();
    }
    
    /**
     * Perform validation
     */
    protected function validate()
    {
        foreach ($this->rules as $field => $rules) {
            $value = $this->data[$field] ?? null;
            $fieldRules = is_string($rules) ? explode('|', $rules) : $rules;
            
            foreach ($fieldRules as $rule) {
                $this->validateField($field, $value, $rule);
            }
            
            // If no errors for this field, add to validated data
            if (!isset($this->errors[$field])) {
                $this->validated[$field] = $value;
            }
        }
    }
    
    /**
     * Validate single field
     */
    protected function validateField($field, $value, $rule)
    {
        // Parse rule and parameters
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $parameters = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];
        
        $method = 'validate' . ucfirst($ruleName);
        
        if (method_exists($this, $method)) {
            $result = $this->$method($field, $value, $parameters);
            
            if ($result !== true) {
                $this->addError($field, $ruleName, $parameters, $result);
            }
        }
    }
    
    /**
     * Add error message
     */
    protected function addError($field, $rule, $parameters, $message = null)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        
        $errorMessage = $message ?: $this->getErrorMessage($field, $rule, $parameters);
        $this->errors[$field][] = $errorMessage;
    }
    
    /**
     * Get error message
     */
    protected function getErrorMessage($field, $rule, $parameters)
    {
        $key = "{$field}.{$rule}";
        
        if (isset($this->messages[$key])) {
            return $this->messages[$key];
        }
        
        $messages = [
            'required' => "The {$field} field is required.",
            'email' => "The {$field} must be a valid email address.",
            'min' => "The {$field} must be at least {$parameters[0]} characters.",
            'max' => "The {$field} may not be greater than {$parameters[0]} characters.",
            'numeric' => "The {$field} must be a number.",
            'integer' => "The {$field} must be an integer.",
            'string' => "The {$field} must be a string.",
            'array' => "The {$field} must be an array.",
            'boolean' => "The {$field} must be true or false.",
            'date' => "The {$field} is not a valid date.",
            'url' => "The {$field} format is invalid.",
            'confirmed' => "The {$field} confirmation does not match.",
            'same' => "The {$field} and {$parameters[0]} must match.",
            'different' => "The {$field} and {$parameters[0]} must be different.",
            'in' => "The selected {$field} is invalid.",
            'not_in' => "The selected {$field} is invalid.",
            'unique' => "The {$field} has already been taken.",
            'exists' => "The selected {$field} is invalid.",
            'regex' => "The {$field} format is invalid.",
            'alpha' => "The {$field} may only contain letters.",
            'alpha_num' => "The {$field} may only contain letters and numbers.",
            'alpha_dash' => "The {$field} may only contain letters, numbers, dashes and underscores."
        ];
        
        return $messages[$rule] ?? "The {$field} field is invalid.";
    }
    
    // Validation Rules
    
    protected function validateRequired($field, $value, $parameters)
    {
        return !empty($value) || $value === '0' || $value === 0;
    }
    
    protected function validateEmail($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    protected function validateMin($field, $value, $parameters)
    {
        if (empty($value)) return true;
        
        // Check if min parameter is provided
        if (empty($parameters) || !isset($parameters[0])) {
            return true; // Skip validation if no min parameter provided
        }
        
        $min = $parameters[0];
        
        if (is_numeric($value)) {
            return $value >= $min;
        }
        
        return strlen($value) >= $min;
    }
    
    protected function validateMax($field, $value, $parameters)
    {
        if (empty($value)) return true;
        
        // Check if max parameter is provided
        if (empty($parameters) || !isset($parameters[0])) {
            return true; // Skip validation if no max parameter provided
        }
        
        $max = $parameters[0];
        $length = is_array($value) ? count($value) : mb_strlen(trim((string)$value));
        
        log_debug('Validasi max', [
            'field' => $field,
            'value' => $value,
            'length' => $length,
            'max' => $max
        ]);
        
        return $length <= $max;
    }
    
    protected function validateNumeric($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return is_numeric($value);
    }
    
    protected function validateInteger($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
    
    protected function validateString($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return is_string($value);
    }
    
    protected function validateArray($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return is_array($value);
    }
    
    protected function validateBoolean($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return in_array($value, [true, false, 0, 1, '0', '1', 'true', 'false'], true);
    }
    
    protected function validateDate($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return strtotime($value) !== false;
    }
    
    protected function validateUrl($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }
    
    protected function validateConfirmed($field, $value, $parameters)
    {
        $confirmField = $field . '_confirmation';
        $confirmValue = $this->data[$confirmField] ?? null;
        return $value === $confirmValue;
    }
    
    protected function validateSame($field, $value, $parameters)
    {
        if (empty($parameters) || !isset($parameters[0])) {
            return true; // Skip validation if no field parameter provided
        }
        
        $otherField = $parameters[0];
        $otherValue = $this->data[$otherField] ?? null;
        return $value === $otherValue;
    }

    protected function validateDifferent($field, $value, $parameters)
    {
        if (empty($parameters) || !isset($parameters[0])) {
            return true; // Skip validation if no field parameter provided
        }
        
        $otherField = $parameters[0];
        $otherValue = $this->data[$otherField] ?? null;
        return $value !== $otherValue;
    }
    
    protected function validateIn($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return in_array($value, $parameters);
    }
    
    protected function validateNotIn($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return !in_array($value, $parameters);
    }
    
    protected function validateRegex($field, $value, $parameters)
    {
        if (empty($value)) return true;
        
        if (empty($parameters) || !isset($parameters[0])) {
            return true; // Skip validation if no pattern parameter provided
        }
        
        $pattern = $parameters[0];
        return preg_match($pattern, $value);
    }
    
    protected function validateAlpha($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return preg_match('/^[a-zA-Z]+$/', $value);
    }
    
    protected function validateAlphaNum($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return preg_match('/^[a-zA-Z0-9]+$/', $value);
    }
    
    protected function validateAlphaDash($field, $value, $parameters)
    {
        if (empty($value)) return true;
        return preg_match('/^[a-zA-Z0-9_-]+$/', $value);
    }
    
    /**
     * Check if validation failed
     */
    public function fails()
    {
        return !empty($this->errors);
    }
    
    /**
     * Check if validation passed
     */
    public function passes()
    {
        return empty($this->errors);
    }
    
    /**
     * Get all errors
     */
    public function errors()
    {
        return $this->errors;
    }
    
    /**
     * Get first error for field
     */
    public function first($field)
    {
        return $this->errors[$field][0] ?? null;
    }
    
    /**
     * Get validated data
     */
    public function validated()
    {
        return $this->validated;
    }
    
    /**
     * Get all error messages as flat array
     */
    public function getErrorMessages()
    {
        $messages = [];
        foreach ($this->errors as $fieldErrors) {
            $messages = array_merge($messages, $fieldErrors);
        }
        return $messages;
    }
}