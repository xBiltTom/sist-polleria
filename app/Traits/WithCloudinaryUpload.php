<?php

namespace App\Traits;

use Cloudinary\Cloudinary;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait WithCloudinaryUpload
{
    /**
     * Estado de carga de la imagen
     */
    public bool $isUploading = false;

    /**
     * URL temporal para previsualización antes de guardar
     */
    public ?string $previewUrl = null;

    /**
     * Sube una imagen a Cloudinary
     *
     * @param TemporaryUploadedFile $file Archivo temporal de Livewire
     * @param string $folder Carpeta en Cloudinary (ej: 'empleados', 'productos')
     * @return array ['url' => string, 'public_id' => string]
     */
    public function uploadToCloudinary(TemporaryUploadedFile $file, string $folder): array
    {
        $this->isUploading = true;

        try {
            // Obtener la instancia de Cloudinary del contenedor de Laravel
            $cloudinary = app(Cloudinary::class);

            // Subir la imagen usando la API de Cloudinary
            $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => "polleria/{$folder}",
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                ]
            ]);

            return [
                'url' => $result['secure_url'],
                'public_id' => $result['public_id'],
            ];
        } finally {
            $this->isUploading = false;
        }
    }

    /**
     * Elimina una imagen de Cloudinary
     *
     * @param string|null $publicId El public_id de la imagen
     * @return bool
     */
    public function deleteFromCloudinary(?string $publicId): bool
    {
        if (empty($publicId)) {
            return false;
        }

        try {
            $cloudinary = app(Cloudinary::class);
            $cloudinary->uploadApi()->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Genera una URL de previsualización para el archivo temporal
     *
     * @param TemporaryUploadedFile|null $file
     * @return string|null
     */
    public function getPreviewUrl($file): ?string
    {
        if ($file instanceof TemporaryUploadedFile) {
            return $file->temporaryUrl();
        }
        return null;
    }

    /**
     * Procesa la subida de imagen y devuelve los datos para guardar en el modelo
     *
     * @param mixed $file El archivo subido (puede ser null, string o TemporaryUploadedFile)
     * @param string $folder Carpeta en Cloudinary
     * @param string|null $oldPublicId El public_id anterior para eliminarlo
     * @return array|null ['url' => string, 'public_id' => string] o null si no hay archivo
     */
    public function processImageUpload($file, string $folder, ?string $oldPublicId = null): ?array
    {
        if (!$file instanceof TemporaryUploadedFile) {
            return null;
        }

        // Si hay una imagen anterior, eliminarla
        if ($oldPublicId) {
            $this->deleteFromCloudinary($oldPublicId);
        }

        return $this->uploadToCloudinary($file, $folder);
    }

    /**
     * Resetea el estado de previsualización
     */
    public function resetPreview(): void
    {
        $this->previewUrl = null;
        $this->isUploading = false;
    }
}
