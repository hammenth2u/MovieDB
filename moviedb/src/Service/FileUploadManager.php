<?php

namespace App\Service;

use Symfony\Component\Form\Form;

class FileUploadManager
{
    private $targetFolderPath;

    public function __construct(string $targetFolderPath)
    {
        $this->targetFolderPath = $targetFolderPath;
    }

    public function upload(Form $formFile, int $movieId): ?string
    {
        // $formFile ici est un objet de la classe Form qui contient les données du champs «image»
        // On utilise getData() pour obtenir un objet de la classe UploadedFile
        
        $file = $formFile->getData();
        // dump($file);
        // On sait que si aucun fichier n'a été envoyé, $file sera null
        if ($file !== null) {
            $filmenameToStore = $movieId . '.' . $file->getClientOriginalExtension();
            $movedFile = $file->move($this->targetFolderPath, $filmenameToStore);
            // $movedFile est un objet de la classe File qui représente le nouveau fichier créé et déplacé après l'upload
            // On utilise sa méthode ->getPathname() pour obtenir le chemin relatif du fichier et le retourner

            return $movedFile->getPathname();
        } else {
            return null;
        }
    }
}