<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
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
            TextField::new('title')->setRequired(true),
            TextEditorField::new('short_description')->setRequired(true),
            ImageField::new('image')
                ->setUploadDir('public/uploads/posts')
                ->setBasePath('/uploads/posts')
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')
                ->setRequired(true),
            TextEditorField::new('content')->setRequired(true),
            AssociationField::new('categories')
                ->setFormTypeOption('by_reference', false)
                ->setFormTypeOptions([
                    'multiple' => true,
                    'expanded' => false, // false = dropdown, true = checkboxes
                ])
                ->setLabel('Categories')
                ->setFormTypeOption('choice_label', 'name'),
        ];
    }



}
