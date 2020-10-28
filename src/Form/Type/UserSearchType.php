<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\{City, Province};
use App\Form\UserSearchForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    ChoiceType,
    ResetType,
    SubmitType,
    TextType
};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSearchType extends AbstractType
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $provinceArray = [];
        $cityArray = [];

        $pr = $this->em->getRepository(Province::class);
        $cr = $this->em->getRepository(City::class);

        $provinceList = $pr->getProvinceList();
        $cityList = $cr->getCityList($options['data']->getProvince());

        $provinceArray[' '] = 0;
        foreach ($provinceList as $province) {
            $provinceArray[$province->getName()] = $province->getId();
        }
        $cityArray[' '] = 0;
        foreach ($cityList as $city) {
            $cityArray[$city->getName()] = $city->getId();
        }

        $builder
            ->add('name', TextType::class, [
                'label' => 'label.name',
                'required' => false
            ])
            ->add('surname', TextType::class, [
                'label' => 'label.surname',
                'required' => false
            ])
            ->add('province', ChoiceType::class, [
                'label' => 'label.province',
                'choices' => $provinceArray
            ])
            ->add('city', ChoiceType::class, [
                'label' => 'label.city',
                'choices' => $cityArray
            ])
            ->add('save', SubmitType::class, ['label' => 'label.submit'])
            ->add('reset', ResetType::class, ['label' => 'label.clear'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSearchForm::class,
            'csrf_protection' => true
        ]);
    }
}
