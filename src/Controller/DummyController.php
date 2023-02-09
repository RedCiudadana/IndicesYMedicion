<?php

namespace App\Controller;

use App\Entity\MeasurementIndex;
use App\Entity\SurveySubmit;
use App\Repository\SurveySubmitRepository;
use Jawira\CaseConverter\Convert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

class DummyController extends AbstractController
{
    /**
     * @Route("/encuesta/{id}", name="app_survey_form")
     * @ParamConverter("measurementIndex", class="App\Entity\MeasurementIndex")
     */
    public function index(
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
            return $this->redirectToRoute('app_survey_form', [
                'id' => $measurementIndex->getId()
            ]);
        }

        return $this->render('dummy/index.html.twig', [
            'measurementIndex' => $measurementIndex,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/encuesta/{id}/data", name="app_survey_data")
     * @ParamConverter("measurementIndex", class="App\Entity\MeasurementIndex")
     */
    public function getMeasurementIndexData(
        MeasurementIndex $measurementIndex,
        SurveySubmitRepository $surveySubmitRepository,
        Request $request
    ): Response {
        $data = $surveySubmitRepository->findBy([
            'measurementIndex' => $measurementIndex
        ]);

        return $this->render('dummy/data.html.twig', [
            'measurementIndex' => $measurementIndex,
            'data' => $data
        ]);
    }
}
