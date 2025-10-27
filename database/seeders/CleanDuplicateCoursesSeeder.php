<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanDuplicateCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los cursos agrupados por nombre
        $coursesGroups = DB::table('courses')
            ->select('name', DB::raw('MIN(id) as min_id'), DB::raw('COUNT(*) as count'))
            ->groupBy('name')
            ->get();
            
        foreach ($coursesGroups as $group) {
            if ($group->count > 1) {
                // Obtener el curso con el ID más bajo (el original)
                $originalCourse = Course::find($group->min_id);
                
                // Encontrar todos los cursos duplicados
                $duplicates = Course::where('name', $group->name)
                    ->where('id', '!=', $group->min_id)
                    ->get();
                    
                foreach ($duplicates as $duplicate) {
                    // Actualizar las preguntas relacionadas al duplicado para que usen el curso original
                    Question::where('course_id', $duplicate->id)
                        ->update(['course_id' => $originalCourse->id]);
                        
                    // Manejar las relaciones en las tablas pivot
                    if (Schema::hasTable('career_course')) {
                        // En lugar de actualizar, eliminamos las relaciones duplicadas
                        // para evitar problemas de clave única
                        DB::table('career_course')
                            ->where('course_id', $duplicate->id)
                            ->delete();
                    }
                    
                    // Eliminar el curso duplicado
                    $duplicate->delete();
                    
                    $this->command->info("Eliminado curso duplicado ID: {$duplicate->id}, Nombre: {$duplicate->name}");
                }
            }
        }
        
        $this->command->info("Limpieza de cursos duplicados completada.");
    }
}