<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Repository\CategoryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                'label' => 'Category name'
                ]
            )
            ->add(
                'parent',
                EntityType::class,
                [
                    'label' => 'Subcategory of',
                    'required' => false,
                    'class' => Category::class,
                    'placeholder' => 'None',
                    'query_builder' => function (EntityRepository $er) {
                        $qb = $er->createQueryBuilder('e');
                        $qb->where("e.parent IS NULL");

                        return $qb;
                    },
                ]
            )
            ->add('save', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Category',
            )
        );
    }

}
