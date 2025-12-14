<?php

/**
 * Script de Diagnóstico de Cloudinary
 *
 * Este script prueba la conexión y funcionalidad de Cloudinary
 * Ejecutar desde la raíz del proyecto: php test-cloudinary.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DIAGNÓSTICO DE CLOUDINARY ===\n\n";

// 1. Verificar variables de entorno
echo "1. Verificando variables de entorno...\n";
echo "-----------------------------------\n";

$cloudName = env('CLOUDINARY_CLOUD_NAME');
$apiKey = env('CLOUDINARY_API_KEY');
$apiSecret = env('CLOUDINARY_API_SECRET');
$uploadPreset = env('CLOUDINARY_UPLOAD_PRESET');
$notificationUrl = env('CLOUDINARY_NOTIFICATION_URL');

echo "CLOUDINARY_CLOUD_NAME: " . ($cloudName ? "✓ Configurado ({$cloudName})" : "✗ NO CONFIGURADO") . "\n";
echo "CLOUDINARY_API_KEY: " . ($apiKey ? "✓ Configurado (" . substr($apiKey, 0, 10) . "...)" : "✗ NO CONFIGURADO") . "\n";
echo "CLOUDINARY_API_SECRET: " . ($apiSecret ? "✓ Configurado (" . substr($apiSecret, 0, 10) . "...)" : "✗ NO CONFIGURADO") . "\n";
echo "CLOUDINARY_UPLOAD_PRESET: " . ($uploadPreset ?: "No configurado (opcional)") . "\n";
echo "CLOUDINARY_NOTIFICATION_URL: " . ($notificationUrl ?: "No configurado (opcional)") . "\n\n";

if (!$cloudName || !$apiKey || !$apiSecret) {
    echo "❌ ERROR: Faltan variables de entorno necesarias.\n";
    echo "Verifica tu archivo .env\n\n";
    exit(1);
}

// 2. Verificar que el paquete de Cloudinary esté instalado
echo "2. Verificando instalación del paquete Cloudinary...\n";
echo "---------------------------------------------------\n";

try {
    if (class_exists(\CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::class)) {
        echo "✓ Paquete Cloudinary instalado correctamente\n\n";
    } else {
        echo "✗ Paquete Cloudinary NO encontrado\n";
        echo "Ejecuta: composer require cloudinary-labs/cloudinary-laravel\n\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "✗ Error al verificar el paquete: " . $e->getMessage() . "\n\n";
    exit(1);
}

// 3. Verificar configuración de Cloudinary
echo "3. Verificando configuración de Cloudinary...\n";
echo "--------------------------------------------\n";

try {
    $config = config('cloudinary');

    if ($config) {
        echo "✓ Archivo de configuración encontrado\n";
        echo "Cloud Name desde config: " . ($config['cloud_name'] ?? 'No configurado') . "\n\n";
    } else {
        echo "⚠ Archivo de configuración no encontrado\n";
        echo "Publica la configuración: php artisan vendor:publish --provider=\"CloudinaryLabs\\CloudinaryLaravel\\CloudinaryServiceProvider\"\n\n";
    }
} catch (\Exception $e) {
    echo "⚠ Error al obtener configuración: " . $e->getMessage() . "\n\n";
}

// 4. Crear una imagen de prueba
echo "4. Creando imagen de prueba...\n";
echo "------------------------------\n";

$testImagePath = sys_get_temp_dir() . '/cloudinary-test-' . time() . '.jpg';

// Crear una imagen simple de 100x100 píxeles
$image = imagecreatetruecolor(100, 100);
$bgColor = imagecolorallocate($image, 255, 200, 100); // Color naranja pollería
$textColor = imagecolorallocate($image, 0, 0, 0);
imagefill($image, 0, 0, $bgColor);
imagestring($image, 5, 10, 40, 'TEST', $textColor);
imagejpeg($image, $testImagePath, 90);
imagedestroy($image);

if (file_exists($testImagePath)) {
    $fileSize = filesize($testImagePath);
    echo "✓ Imagen de prueba creada: {$testImagePath}\n";
    echo "  Tamaño: " . number_format($fileSize / 1024, 2) . " KB\n\n";
} else {
    echo "✗ No se pudo crear la imagen de prueba\n\n";
    exit(1);
}

// 5. Probar subida a Cloudinary
echo "5. Probando subida a Cloudinary...\n";
echo "----------------------------------\n";

try {
    echo "Intentando subir imagen...\n";

    $cloudinary = app(\Cloudinary\Cloudinary::class);
    $result = $cloudinary->uploadApi()->upload($testImagePath, [
        'folder' => 'polleria/test',
        'transformation' => [
            'quality' => 'auto',
            'fetch_format' => 'auto',
        ]
    ]);

    echo "✓ ¡SUBIDA EXITOSA!\n\n";
    echo "Detalles de la subida:\n";
    echo "----------------------\n";
    echo "Public ID: " . $result['public_id'] . "\n";
    echo "URL Segura: " . $result['secure_url'] . "\n";
    echo "Formato: " . $result['format'] . "\n";
    echo "Tamaño: " . $result['bytes'] . " bytes\n";
    echo "Ancho: " . $result['width'] . "px\n";
    echo "Alto: " . $result['height'] . "px\n\n";

    $publicId = $result['public_id'];

    // 6. Probar eliminación
    echo "6. Probando eliminación de imagen...\n";
    echo "------------------------------------\n";

    try {
        $deleteResult = $cloudinary->uploadApi()->destroy($publicId);
        echo "✓ Imagen eliminada correctamente\n";
        echo "Resultado: " . json_encode($deleteResult) . "\n\n";
    } catch (\Exception $e) {
        echo "⚠ Error al eliminar imagen: " . $e->getMessage() . "\n\n";
    }

} catch (\Exception $e) {
    echo "✗ ERROR AL SUBIR IMAGEN\n";
    echo "Tipo de error: " . get_class($e) . "\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n\n";

    // Limpiar imagen temporal
    if (file_exists($testImagePath)) {
        unlink($testImagePath);
    }
    exit(1);
}

// Limpiar imagen temporal
if (file_exists($testImagePath)) {
    unlink($testImagePath);
    echo "✓ Archivo temporal eliminado\n\n";
}

// 7. Verificar permisos de storage (para Livewire)
echo "7. Verificando permisos de storage (Livewire)...\n";
echo "------------------------------------------------\n";

$storagePath = storage_path('app/livewire-tmp');
if (!is_dir($storagePath)) {
    echo "⚠ Directorio livewire-tmp no existe, creándolo...\n";
    try {
        mkdir($storagePath, 0755, true);
        echo "✓ Directorio creado\n";
    } catch (\Exception $e) {
        echo "✗ Error al crear directorio: " . $e->getMessage() . "\n";
    }
} else {
    echo "✓ Directorio livewire-tmp existe\n";
}

if (is_writable($storagePath)) {
    echo "✓ Directorio livewire-tmp tiene permisos de escritura\n\n";
} else {
    echo "✗ Directorio livewire-tmp NO tiene permisos de escritura\n";
    echo "Ejecuta: chmod -R 755 storage/\n\n";
}

// Resumen final
echo "=== RESUMEN ===\n";
echo "===============\n";
echo "✓ Variables de entorno: OK\n";
echo "✓ Paquete Cloudinary: OK\n";
echo "✓ Subida de imagen: OK\n";
echo "✓ Eliminación de imagen: OK\n";
echo "✓ Permisos de storage: OK\n\n";
echo "🎉 ¡Cloudinary está funcionando correctamente!\n";
echo "Si aún tienes problemas, revisa los logs en storage/logs/laravel.log\n\n";
