<?php

namespace App\Controller\Admin;

use App\Entity\Trip;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Bundle\SecurityBundle\Security;

class TripCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public static function getEntityFqcn(): string
    {
        return Trip::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextEditorField::new('description'),
            TextField::new('destination'),
            DateField::new('startDate'),
            DateField::new('endDate'),
            TextField::new('image')->setLabel('Image URL')->hideOnIndex(),
            ImageField::new('image')->setBasePath('')->onlyOnIndex(),
            AssociationField::new('user')
            ->hideOnForm(),
        ];
    }
    

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $user = $this->security->getUser();

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        }

        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $queryBuilder->andWhere('entity.user = :user')
                    ->setParameter('user', $user);
        return $queryBuilder;
        
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Trip) {
            return;
        }

        if (!$this->security->isGranted('ROLE_ADMIN')) {
            $entityInstance->setUser($this->security->getUser());
        }
        parent::persistEntity($entityManager, $entityInstance);
    }
}
