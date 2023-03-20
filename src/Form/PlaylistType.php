<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Description of PlaylistType
 *
 * @author uu0✿
 */
class PlaylistType extends AbstractType {
    
    /**
     * Initialisation du formulaire
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {

        $builder
            ->add('name', TextType::class, [
                'label' => 'Intitulé de la playlist',
                'required' => true,
                'constraints' => [
                    new Length(['min' => 6, 'max' => 100])
                ]    
            ])
            ->add('formations', EntityType::class, [
                'class' => Formation::class,
                'label' => 'Titre de la formation',
                'choice_label' => 'title',
                'multiple' => true,
                'required' => false,
                'disabled' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la nouvelle playlist',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }

}
