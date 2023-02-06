<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MeasurementIndex;
use App\Entity\SurveySubmit;
use App\Repository\SurveySubmitRepository;
use Jawira\CaseConverter\Convert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

final class MeasurementIndexAdminController extends CRUDController
{
    /**
     * @ParamConverter("measurementIndex", class="App\Entity\MeasurementIndex")
     */
    public function submitSurveyAction(
        MeasurementIndex $measurementIndex,
        SurveySubmitRepository $surveySubmitRepository,
        Request $request
    ): Response
    {
        $formBuilder = $this->createFormBuilder();

        foreach ($measurementIndex->getSurveyQuestions() as $question) {
            $formType = '';
            $formOptions = [];

            switch ($question->getFormType()) {
                case 'choice':
                    $formType = ChoiceType::class;

                    $formOptions = array_merge($formOptions, $question->getFormOptions() ?? []);
                    $formOptions = array_merge($formOptions, [
                        'choices' => array_combine(
                            array_values($question->getChoices()),
                            array_values($question->getChoices())
                        )
                    ]);

                    break;

                case 'number':
                    $formType = NumberType::class;

                    $formOptions = array_merge($formOptions, $question->getFormOptions() ?? []);
                    $formOptions = array_merge($formOptions, [
                        'constraints' => [
                            new Assert\Range([
                                'min' => $question->getMin(),
                                'max' => $question->getMax()
                            ])
                        ]
                    ]);
                    break;

                default:
                    break;
            }

            $converter = new Convert($question->getName());
            $formBuilder->add($converter->toCamel(), $formType, $formOptions);
        }

        $formBuilder->add('submit', SubmitType::class, [
            'label' => 'Enviar información'
        ]);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $surveySubmit = new SurveySubmit();
            $surveySubmit->setMeasurementIndex($measurementIndex);
            $surveySubmit->setSubmittedBy($this->getUser());
            $surveySubmit->setSubmittedData($form->getData());

            $surveySubmitRepository->add($surveySubmit, true);

            $this->addFlash('success', 'Se envio correctamente la encuesta');

            // TODO: mostrar flashes aqui
            return $this->redirectToRoute('admin_app_measurementindex_submitSurvey', [
                'id' => $measurementIndex->getId()
            ]);
        }

        return $this->render('survey_submit/create.html.twig',
            [
                'measurementIndex' => $measurementIndex,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @ParamConverter("measurementIndex", class="App\Entity\MeasurementIndex")
     */
    public function submitSurveyDataAction(
        MeasurementIndex $measurementIndex,
        SurveySubmitRepository $surveySubmitRepository,
        Request $request
    ): Response {
        $data = $surveySubmitRepository->findBy([
            'measurementIndex' => $measurementIndex
        ]);

        return $this->render('survey_submit/data.html.twig', [
            'measurementIndex' => $measurementIndex,
            'data' => $data
        ]);
    }
}
