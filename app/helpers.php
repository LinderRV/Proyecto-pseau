<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

/**
 * Personaliza los mensajes de validación para que aparezcan en español
 */
function customValidationMessages()
{
    return [
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'Ingrese un correo electrónico válido.',
        'password.required' => 'La contraseña es obligatoria.',
        'name.required' => 'El nombre es obligatorio.',
        'password.min' => 'La contraseña debe tener al menos :min caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
    ];
}

/**
 * Personaliza los atributos de los campos para que se muestren en español
 */
function customValidationAttributes()
{
    return [
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'name' => 'nombre',
    ];
}

/**
 * Format time in decimal minutes to a human-readable format (HH:MM:SS or MM:SS)
 *
 * @param float $timeInMinutes Time in decimal minutes
 * @param bool $showHours Whether to always show hours even if zero
 * @return string Formatted time string
 */
function formatTime($timeInMinutes, $showHours = true)
{
    // Convert minutes to seconds
    $totalSeconds = $timeInMinutes * 60;
    
    // Calculate hours, minutes, and seconds
    $hours = floor($totalSeconds / 3600);
    $minutes = floor(($totalSeconds % 3600) / 60);
    $seconds = floor($totalSeconds % 60);
    
    // Format the time string
    if ($hours > 0 || $showHours) {
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    } else {
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}

/**
 * Get the color for a specific course based on the subject or course ID
 *
 * @param string|int $identifier The icon key, subject identifier, or course ID
 * @param string $type The type of color to return (border, bg, text, hover, etc.)
 * @return string CSS class for the color
 */
function getCourseColor($identifier, $type = 'border')
{
    // Colors by subject key
    $colorsBySubject = [
        'math' => [
            'border' => 'border-blue-500',
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-600',
            'hover' => 'hover:bg-blue-200',
            'bg-full' => 'bg-blue-500',
            'bg-light' => 'bg-blue-50'
        ],
        'chemistry' => [
            'border' => 'border-green-500',
            'bg' => 'bg-green-100',
            'text' => 'text-green-600',
            'hover' => 'hover:bg-green-200',
            'bg-full' => 'bg-green-500',
            'bg-light' => 'bg-green-50'
        ],
        'physics' => [
            'border' => 'border-purple-500',
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-600',
            'hover' => 'hover:bg-purple-200',
            'bg-full' => 'bg-purple-500',
            'bg-light' => 'bg-purple-50'
        ],
        'biology' => [
            'border' => 'border-pink-500',
            'bg' => 'bg-pink-100',
            'text' => 'text-pink-600',
            'hover' => 'hover:bg-pink-200',
            'bg-full' => 'bg-pink-500',
            'bg-light' => 'bg-pink-50'
        ],
        'history' => [
            'border' => 'border-amber-500',
            'bg' => 'bg-amber-100',
            'text' => 'text-amber-600',
            'hover' => 'hover:bg-amber-200',
            'bg-full' => 'bg-amber-500',
            'bg-light' => 'bg-amber-50'
        ],
        'language' => [
            'border' => 'border-indigo-500',
            'bg' => 'bg-indigo-100',
            'text' => 'text-indigo-600',
            'hover' => 'hover:bg-indigo-200',
            'bg-full' => 'bg-indigo-500',
            'bg-light' => 'bg-indigo-50'
        ],
        'literature' => [
            'border' => 'border-indigo-500',
            'bg' => 'bg-indigo-100',
            'text' => 'text-indigo-600',
            'hover' => 'hover:bg-indigo-200',
            'bg-full' => 'bg-indigo-500',
            'bg-light' => 'bg-indigo-50'
        ],
        'programming' => [
            'border' => 'border-cyan-500',
            'bg' => 'bg-cyan-100',
            'text' => 'text-cyan-600',
            'hover' => 'hover:bg-cyan-200',
            'bg-full' => 'bg-cyan-500',
            'bg-light' => 'bg-cyan-50'
        ],
        'economics' => [
            'border' => 'border-orange-500',
            'bg' => 'bg-orange-100',
            'text' => 'text-orange-600',
            'hover' => 'hover:bg-orange-200',
            'bg-full' => 'bg-orange-500',
            'bg-light' => 'bg-orange-50'
        ]
    ];
    
    // Colors by course ID (from dashboard)
    $colorsById = [
        1 => [
            'border' => 'border-green-500',
            'bg' => 'bg-green-100',
            'text' => 'text-green-600',
            'hover' => 'hover:bg-green-200',
            'bg-full' => 'bg-green-500',
            'bg-light' => 'bg-green-50'
        ],
        2 => [
            'border' => 'border-blue-500',
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-600',
            'hover' => 'hover:bg-blue-200',
            'bg-full' => 'bg-blue-500',
            'bg-light' => 'bg-blue-50'
        ],
        3 => [
            'border' => 'border-yellow-500',
            'bg' => 'bg-yellow-100',
            'text' => 'text-yellow-600',
            'hover' => 'hover:bg-yellow-200',
            'bg-full' => 'bg-yellow-500',
            'bg-light' => 'bg-yellow-50'
        ],
        4 => [
            'border' => 'border-teal-500',
            'bg' => 'bg-teal-100',
            'text' => 'text-teal-600',
            'hover' => 'hover:bg-teal-200',
            'bg-full' => 'bg-teal-500',
            'bg-light' => 'bg-teal-50'
        ],
        5 => [
            'border' => 'border-amber-500',
            'bg' => 'bg-amber-100',
            'text' => 'text-amber-600',
            'hover' => 'hover:bg-amber-200',
            'bg-full' => 'bg-amber-500',
            'bg-light' => 'bg-amber-50'
        ],
        6 => [
            'border' => 'border-rose-500',
            'bg' => 'bg-rose-100',
            'text' => 'text-rose-600',
            'hover' => 'hover:bg-rose-200',
            'bg-full' => 'bg-rose-500',
            'bg-light' => 'bg-rose-50'
        ],
        7 => [
            'border' => 'border-purple-500',
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-600',
            'hover' => 'hover:bg-purple-200',
            'bg-full' => 'bg-purple-500',
            'bg-light' => 'bg-purple-50'
        ],
        8 => [
            'border' => 'border-indigo-500',
            'bg' => 'bg-indigo-100',
            'text' => 'text-indigo-600',
            'hover' => 'hover:bg-indigo-200',
            'bg-full' => 'bg-indigo-500',
            'bg-light' => 'bg-indigo-50'
        ]
    ];
    
    // Default colors
    $defaultColors = [
        'border' => 'border-gray-500',
        'bg' => 'bg-gray-100',
        'text' => 'text-gray-600',
        'hover' => 'hover:bg-gray-200',
        'bg-full' => 'bg-gray-500',
        'bg-light' => 'bg-gray-50'
    ];

    // Check if identifier is numeric (course ID)
    if (is_numeric($identifier) && isset($colorsById[$identifier]) && isset($colorsById[$identifier][$type])) {
        return $colorsById[$identifier][$type];
    }
    
    // Check if identifier is a string (subject key)
    if (is_string($identifier) && isset($colorsBySubject[$identifier]) && isset($colorsBySubject[$identifier][$type])) {
        return $colorsBySubject[$identifier][$type];
    }

    // Return default color if no match found
    return $defaultColors[$type] ?? $defaultColors['bg'];
}