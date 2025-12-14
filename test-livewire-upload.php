<?php

/**
 * Script de Prueba de Subida de Archivos con Livewire
 *
 * Este script simula el proceso de subida de archivos que hace Livewire
 * Ejecutar: php test-livewire-upload.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PRUEBA DE SUBIDA DE ARCHIVOS LIVEWIRE + CLOUDINARY ===\n\n";

// 1. Verificar que Livewire esté instalado
echo "1. Verificando Livewire...\n";
echo "-------------------------\n";

if (class_exists(\Livewire\Features\SupportFileUploads\TemporaryUploadedFile::class)) {
    echo "✓ Livewire instalado correctamente\n\n";
} else {
    echo "✗ Livewire NO encontrado\n\n";
    exit(1);
}

// 2. Verificar el trait WithCloudinaryUpload
echo "2. Verificando trait WithCloudinaryUpload...\n";
echo "--------------------------------------------\n";

try {
    if (trait_exists(\App\Traits\WithCloudinaryUpload::class)) {
        echo "✓ Trait WithCloudinaryUpload encontrado\n\n";

        // Mostrar métodos del trait
        $reflection = new ReflectionClass(\App\Traits\WithCloudinaryUpload::class);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

        echo "Métodos públicos disponibles:\n";
        foreach ($methods as $method) {
            if (!$method->isConstructor() && !$method->isDestructor()) {
                echo "  - " . $method->getName() . "()\n";
            }
        }
        echo "\n";
    } else {
        echo "✗ Trait WithCloudinaryUpload NO encontrado\n\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// 3. Simular el proceso de Livewire
echo "3. Simulando proceso de Livewire...\n";
echo "-----------------------------------\n";

// Crear una imagen de prueba
$testImagePath = sys_get_temp_dir() . '/livewire-test-' . time() . '.jpg';
$image = imagecreatetruecolor(200, 200);
$bgColor = imagecolorallocate($image, 255, 150, 50);
$textColor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $bgColor);
imagestring($image, 5, 50, 90, 'LIVEWIRE', $textColor);
imagejpeg($image, $testImagePath, 90);
imagedestroy($image);

echo "✓ Imagen de prueba creada: {$testImagePath}\n";
echo "  Tamaño: " . number_format(filesize($testImagePath) / 1024, 2) . " KB\n\n";

// 4. Probar la subida usando el método del trait
echo "4. Probando subida con WithCloudinaryUpload...\n";
echo "-----------------------------------------------\n";

// Crear una clase de prueba que use el trait
$testClass = new class {
    use \App\Traits\WithCloudinaryUpload;

    public function testUpload($filePath, $folder) {
        try {
            echo "  → Verificando que el archivo existe...\n";
            if (!file_exists($filePath)) {
                throw new \Exception("El archivo no existe: {$filePath}");
            }
            echo "    ✓ Archivo existe\n";

            echo "  → Verificando permisos de lectura...\n";
            if (!is_readable($filePath)) {
                throw new \Exception("El archivo no es legible: {$filePath}");
            }
            echo "    ✓ Archivo es legible\n";

            echo "  → Intentando subir a Cloudinary directamente...\n";

            // Usar directamente la API de Cloudinary como lo hace el trait
            $cloudinary = app(\Cloudinary\Cloudinary::class);
            $result = $cloudinary->uploadApi()->upload($filePath, [
                'folder' => "polleria/{$folder}",
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                ]
            ]);

            return [
                'url' => $result['secure_url'],
                'public_id' => $result['public_id']
            ];
        } catch (\Exception $e) {
            echo "    ✗ Error: " . $e->getMessage() . "\n";
            echo "    Tipo: " . get_class($e) . "\n";
            throw $e;
        }
    }

    public function testDelete($publicId) {
        return $this->deleteFromCloudinary($publicId);
    }
};

try {
    $result = $testClass->testUpload($testImagePath, 'test-livewire');

    echo "    ✓ ¡SUBIDA EXITOSA!\n\n";
    echo "Detalles:\n";
    echo "---------\n";
    echo "URL: " . $result['url'] . "\n";
    echo "Public ID: " . $result['public_id'] . "\n\n";

    // Probar eliminación
    echo "5. Probando eliminación...\n";
    echo "-------------------------\n";

    $deleted = $testClass->testDelete($result['public_id']);

    if ($deleted) {
        echo "✓ Imagen eliminada correctamente\n\n";
    } else {
        echo "⚠ No se pudo eliminar la imagen (puede que no exista)\n\n";
    }

} catch (\Exception $e) {
    echo "\n❌ ERROR EN LA PRUEBA\n";
    echo "====================\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n\n";

    // Limpiar
    if (file_exists($testImagePath)) {
        unlink($testImagePath);
    }
    exit(1);
}

// Limpiar
if (file_exists($testImagePath)) {
    unlink($testImagePath);
    echo "✓ Archivo temporal eliminado\n\n";
}

// 6. Probar el componente Empleados/Create
echo "6. Verificando componente Empleados/Create...\n";
echo "---------------------------------------------\n";

try {
    $reflection = new ReflectionClass(\App\Livewire\Empleados\Create::class);

    echo "✓ Clase encontrada\n";

    // Verificar que usa el trait
    $traits = $reflection->getTraitNames();
    if (in_array('App\\Traits\\WithCloudinaryUpload', $traits)) {
        echo "✓ Usa el trait WithCloudinaryUpload\n";
    } else {
        echo "✗ NO usa el trait WithCloudinaryUpload\n";
    }

    if (in_array('Livewire\\WithFileUploads', $traits)) {
        echo "✓ Usa WithFileUploads de Livewire\n";
    } else {
        echo "✗ NO usa WithFileUploads de Livewire\n";
    }

    // Verificar método save
    if ($reflection->hasMethod('save')) {
        echo "✓ Método save() existe\n";

        // Leer el código del método save
        $method = $reflection->getMethod('save');
        $fileName = $method->getFileName();
        $startLine = $method->getStartLine();
        $endLine = $method->getEndLine();

        $lines = file($fileName);
        $methodCode = implode('', array_slice($lines, $startLine - 1, $endLine - $startLine + 1));

        // Verificar que usa uploadToCloudinary
        if (strpos($methodCode, 'uploadToCloudinary') !== false) {
            echo "✓ Método save() usa uploadToCloudinary\n";
        } else {
            echo "✗ Método save() NO usa uploadToCloudinary\n";
        }

        // Verificar que elimina el campo temporal
        if (strpos($methodCode, "unset(\$validated['fotoEmpleado'])") !== false ||
            strpos($methodCode, 'unset($validated[\'fotoEmpleado\'])') !== false) {
            echo "✓ Método save() elimina el campo temporal\n";
        } else {
            echo "⚠ Método save() podría no eliminar el campo temporal\n";
        }
    } else {
        echo "✗ Método save() NO existe\n";
    }

    echo "\n";

} catch (\Exception $e) {
    echo "✗ Error al verificar el componente: " . $e->getMessage() . "\n\n";
}

// Resumen final
echo "=== DIAGNÓSTICO COMPLETO ===\n";
echo "============================\n\n";

echo "Si este script se ejecutó correctamente pero aún tienes problemas:\n\n";
echo "1. Verifica los logs de Laravel: storage/logs/laravel.log\n";
echo "2. Activa el log de errores de Livewire en config/livewire.php\n";
echo "3. Verifica que el formulario tenga: enctype=\"multipart/form-data\"\n";
echo "4. Asegúrate de que el input tenga: wire:model=\"fotoEmpleado\"\n";
echo "5. Verifica que la validación no esté rechazando el archivo\n";
echo "6. Revisa el tamaño máximo de upload en php.ini:\n";
echo "   - upload_max_filesize\n";
echo "   - post_max_size\n";
echo "   - memory_limit\n\n";

echo "Para ver errores en tiempo real:\n";
echo "→ Abre el navegador con F12 (DevTools)\n";
echo "→ Ve a la pestaña 'Network' o 'Red'\n";
echo "→ Intenta subir una imagen\n";
echo "→ Revisa las peticiones HTTP que fallan\n\n";
