<?php

namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Lake;

use Symfony\Component\Form\CallbackTransformer;

use App\Repository\LakeRepository;

class ReserveLakeFormType extends AbstractType
{
    private LakeRepository $lakeRepo;

    public function __construct(LakeRepository $lakeRepo)
    {
        $this->lakeRepo = $lakeRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $lakes = [];
        $queriedLakes = $this->lakeRepo->findAll();

        foreach ($queriedLakes as $value ) {
            $lakes[$value->getName()] = $value->getId();
        }

        $builder
            ->add('beginDate', DateTimeType::class, [
                'label' => 'Od',
                'required' => true
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Do',
                'required' => true
            ])
            ->add('lakeId', ChoiceType::class, [
                'choices' => $lakes
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // $resolver->setDefaults([
        //     'lakeId' => false
        // ]);

        // $resolver->setAllowedTypes('lakeId', 'integer');
    }
};