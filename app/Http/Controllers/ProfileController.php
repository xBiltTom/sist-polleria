<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfileController extends Controller
{
    public function updateEmployee(Request $request)
    {
        $user = auth()->user();

        if (!$user->idEmpleado) {
            return redirect()->back()->with('error', 'No tienes un perfil de empleado asociado.');
        }

        $empleado = $user->empleado;

        $validated = $request->validate([
            'nroCelularEmpleado' => 'required|string|max:9|regex:/^[0-9]{9}$/',
            'urlFotoEmpleado' => 'nullable|image|max:2048'
        ]);

        // Actualizar celular
        $empleado->nroCelularEmpleado = $validated['nroCelularEmpleado'];

        // Manejar la foto si se subió una nueva
        if ($request->hasFile('urlFotoEmpleado')) {
            try {
                // Eliminar la foto anterior de Cloudinary si existe
                if ($empleado->idFotoEmpleado) {
                    Cloudinary::destroy($empleado->idFotoEmpleado);
                }

                // Subir la nueva foto a Cloudinary
                $uploadedFile = Cloudinary::upload($request->file('urlFotoEmpleado')->getRealPath(), [
                    'folder' => 'empleados'
                ]);

                $empleado->urlFotoEmpleado = $uploadedFile->getSecurePath();
                $empleado->idFotoEmpleado = $uploadedFile->getPublicId();
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error al subir la foto: ' . $e->getMessage());
            }
        }

        $empleado->save();

        return redirect()->back()->with('success', 'Datos actualizados correctamente.');
    }
}
