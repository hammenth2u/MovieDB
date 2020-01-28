<?php

namespace App\Controller\Admin;

use App\Entity\Department;
use App\Form\DepartmentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/admin/department", name="admin_department")
     */
    public function index()
    {
        // On cherche à afficher tous les départements
        // On va donc d'abord les chercher dans la base de données
        $departments = $this->getDoctrine()->getRepository(Department::class)->findAll();

        // On demande à Symfony de nous créer un formulaire à partir du FormType d'un Department
        // Ici on a enlevé le deuxième, il n'est pas utile dans ce cas précis
        // Il sert à relier un objet au formulaire pour obtenir un formulaire prérempli
        $form = $this->createForm(DepartmentType::class);

        // On envoie ensuite la liste de Department dans la vue
        return $this->render('admin/department/index.html.twig', [
            'departments' => $departments,
            'formDepartment' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/department/new", name="admin_department_new", methods={"POST"})
     */
    public function new(Request $request)
    {
        // On crée un nouvel objet Department
        $department = new Department();
        // On crée un formulaire DepartmentType qu'on relie à $department
        $form = $this->createForm(DepartmentType::class, $department);
        // On relie les informations reçues en POST avec le formulaire, et donc avec l'objet $department
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($department);
            $em->flush();

            // $this->addFlash('success', 'Le Department a bien été ajouté.');
        }

        // Quoiqu'il arrive, que le formulaire soit bon ou non, on redirige vers la liste des Department
        return $this->redirectToRoute('admin_department');
    }

    /**
     * @Route("/admin/department/{department}/edit", name="admin_department_edit")
     */
    public function edit(Request $request, Department $department)
    {
        // Le but ici est d'afficher un formulaire prérempli des informations d'un Department

        // On obtient l'objet $department grâce à la route (et donc au ParamConverter)
        // On crée un nouveau formulaire associé à $department pour préremplir les champs du form
        $form = $this->createForm(DepartmentType::class, $department);

        // On relie les données reçues en POST avec le formulaire
        $form->handleRequest($request);

        // Il est nécessaire de persister les données
        // …mais on ne le fait que si le formulaire a été envoyé et que les données dedans sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Les conditions sont remplies : on peut persister les données !
            $em = $this->getDoctrine()->getManager();
            $em->persist($department);
            $em->flush();
        }

        return $this->render('admin/department/edit.html.twig', [
            'formDepartment' => $form->createView(),
            'department' => $department
        ]);
    }

    /**
     * @Route("/admin/department/delete", name="admin_department_delete", methods={"POST"})
     */
    public function delete(Request $request)
    {
        /*
        Pour cet exemple on n'a pas mis de paramètre dans la route.
        Ce qui ne nous permet pas de récupérer directement un objet $department
        dans les paramètres de la méthode delete().
        On utilise une technique «à la main» où on code nous même le nécessaire pour récupérer
        l'objet $department à partir de son id reçu dans le formulaire (l'input hidden).
        */

        $id = $request->request->get('department_id');
        $department = $this->getDoctrine()->getRepository(Department::class)->find($id);

        // On souhaite maintenant vérifier le jeton CSRF
        // Il faut d'abord le récupérer depuis la requête
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete-department', $token)) {
            // Ensuite on utilise l'entityManager comme avant avec la méthode ->remove() qui supprime l'objet de la BDD
            $em = $this->getDoctrine()->getManager();
            $em->remove($department);
            $em->flush();

            $this->addFlash('success', 'Le Département a bien été supprimé !');
        } else {
            $this->addFlash('danger', 'Votre formulaire est invalide, veuillez recommencer !');
        }
    

        // une fois fini, on redirige vers la liste des Department
        return $this->redirectToRoute('admin_department');
    }
}
