<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('content'),
            AssociationField::new('categories')
                ->setFormTypeOption('by_reference', false) // REQUIRED for ManyToMany
                ->setFormTypeOptions([
                    'multiple' => true,
                    'expanded' => false, // false = dropdown, true = checkboxes
                ])
                ->setLabel('Categories')
                ->setFormTypeOption('choice_label', 'name'),
        ];
    }



}
